const express = require('express');
const bcrypt  = require('bcrypt');
const jwt     = require('jsonwebtoken');
const multer  = require('multer');
const cors    = require('cors');
const path    = require('path');
const db      = require('./db');

const app    = express();
const SECRET = 'aulim-secret-key';

app.use(cors());
app.use(express.json());
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

const storage = multer.diskStorage({
  destination: (req, file, cb) => cb(null, 'uploads/'),
  filename:    (req, file, cb) => {
    const unique = Date.now() + '-' + Math.round(Math.random() * 1e9);
    cb(null, unique + path.extname(file.originalname));
  },
});
const upload = multer({ storage });

function authMiddleware(req, res, next) {
  const token = req.headers['authorization']?.split(' ')[1];
  if (!token) return res.status(401).json({ error: '로그인이 필요합니다.' });
  try {
    req.user = jwt.verify(token, SECRET);
    next();
  } catch {
    res.status(401).json({ error: '토큰이 유효하지 않습니다.' });
  }
}

// 회원가입
app.post('/api/register', async (req, res) => {
  const { user_id, password } = req.body;
  if (!user_id || !password)
    return res.status(400).json({ error: '아이디와 비밀번호를 입력하세요.' });

  const hashed = await bcrypt.hash(password, 10);
  try {
    await db.execute(
      'INSERT INTO users (user_id, password) VALUES (?, ?)',
      [user_id, hashed]
    );
    res.json({ message: '회원가입 완료!' });
  } catch (e) {
    if (e.code === 'ER_DUP_ENTRY')
      return res.status(409).json({ error: '이미 존재하는 아이디입니다.' });
    res.status(500).json({ error: '서버 오류' });
  }
});

// 로그인
app.post('/api/login', async (req, res) => {
  const { user_id, password } = req.body;
  const [rows] = await db.execute(
    'SELECT * FROM users WHERE user_id = ?', [user_id]
  );
  if (!rows.length)
    return res.status(401).json({ error: '아이디 또는 비밀번호가 틀렸습니다.' });

  const match = await bcrypt.compare(password, rows[0].password);
  if (!match)
    return res.status(401).json({ error: '아이디 또는 비밀번호가 틀렸습니다.' });

  const token = jwt.sign(
    { id: rows[0].id, user_id: rows[0].user_id, role: rows[0].role },
    SECRET,
    { expiresIn: '8h' }
  );
  res.json({ token, user_id: rows[0].user_id, role: rows[0].role });
});

// 파일 업로드
app.post('/api/upload', authMiddleware, upload.single('file'), async (req, res) => {
  if (!req.file)
    return res.status(400).json({ error: '파일을 선택하세요.' });

  await db.execute(
    'INSERT INTO files (filename, original_name, uploaded_by) VALUES (?, ?, ?)',
    [req.file.filename, req.file.originalname, req.user.user_id]
  );
  res.json({
    message: '업로드 완료!',
    url: `/uploads/${req.file.filename}`,
  });
});


// 첨부파일 파일 업로드 기능 수정중


// 파일 목록 조회
app.get('/api/files', async (req, res) => {
  const [rows] = await db.execute(
    'SELECT id, original_name, uploaded_by, created_at FROM files ORDER BY created_at DESC'
  );
  res.json(rows);
});


app.use(express.static(path.join(__dirname, '../web site')));
app.listen(3000, () => console.log('서버 실행 중: http://localhost:3000'));