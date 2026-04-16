# Aulim Security - 웹 서비스 기업 대상 통합 보안 솔루션

<p align="center">
  <img src="https://github.com/user-attachments/assets/d84ea5b3-2a3d-47fc-a442-aa6b625f8feb" width="100%">
</p>

웹 서비스 환경의 보안 위협에 대응할 수 있는 통합 보안 관제 시스템입니다.
실시간 탐지 및 자동 대응이 가능한 안정적인 서비스 운영 환경을 구축합니다.


---

## 프로젝트 개요

### 기본 정보
- 프로젝트명: Aulim Security (어울림)
- 진행 기간: 2026.04.13 ~ 2026.04.24
- 팀 구성: 4명
- 카테고리: 시스템 보안, 네트워크 보안, 로그 분석

### 선정 배경
최근 웹 서비스 기업을 대상으로 한 해킹 및 개인정보 유출 사고가 증가하고 있습니다. 이에 따라 다음과 같은 필요성이 대두되었습니다:
- 네트워크, 서버, 로그 분석을 포함한 통합적인 보안 체계의 필수성
- 실제 기업 환경을 가정한 공격 탐지 및 대응 시스템의 구축 필요
- 보안 관제 센터(SOC) 운영 모델의 실제 구현

---

## 프로젝트 목표 및 기대 효과

### 최종 목표
웹 서비스 환경의 보안 위협을 실시간으로 탐지하고, 로그 기반 분석 및 자동 대응이 가능한 통합 보안 관제 시스템을 구축합니다.

### 예상 결과물

1. 네트워크 아키텍처
   - Cisco Packet Tracer를 활용한 가상 네트워크 환경 구현
   - 실제 보안 관제 센터의 네트워크 구성과 데이터 흐름을 시각적으로 표현

2. 보안 관제 시스템
   - 데이터베이스 이중화(Master-Slave Replication)
   - 안전한 파일 공유 시스템(NFS/SFTP)
   - 비즈니스 연속성 보장

3. 침해 대응 체계
   - 실시간 로그 수집 및 분석
   - 자동 차단 및 알림 시스템
   - 침해 사고 신속 대응

### 기대 효과
- 보안 위협에 대한 실시간 탐지 및 대응
- 내부 정보 유출 및 서비스 장애 사전 방지
- 침해 사고 발생 시 신속한 원인 파악
- 안정적인 서비스 운영 환경 확보

---

## 기술 스택 및 환경 구성

### 인프라 & 네트워크
| 항목 | 기술 | 설명 |
|------|------|------|
| OS | Rocky Linux 9.7 | 보안 강화형 서버 인프라 |
| 가상화 | Oracle VirtualBox 7.2.0 | 가상 서버 환경 구축 |
| 네트워크 | Cisco Packet Tracer 6.1.0 | OSPF, VLAN, VPN 시뮬레이션 |
| 라우팅 프로토콜 | OSPF, HSRP | 경로 이중화 및 동적 라우팅 |
| 방화벽 | firewalld 1.3.0, ACL | 접근 제어 정책 적용 |

### 보안 솔루션
| 항목 | 기술 | 설명 |
|------|------|------|
| IDS/IPS | Fail2Ban 1.0.2 | 무차별 대입 공격(Brute Force) 탐지 및 차단 |
| 접근 제어 | SELinux (Enforcing Mode) | 커널 수준 강제 접근 제어(MAC) |
| VPN | Tailscale | 보안 원격 접속 환경 |

### 데이터베이스 & 스토리지
| 항목 | 기술 | 설명 |
|------|------|------|
| DBMS | MariaDB 11.4.2 LTS | Master-Slave Replication |
| 파일 공유 | NFSv4.2, OpenSSH 9.1 | NFS/SFTP 기반 안전한 데이터 동기화 |
| 스크립트 | Bash, PHP | 로그 처리 및 웹 서비스 |

### 로그 수집 & 분석
| 항목 | 기술 | 설명 |
|------|------|------|
| 로그 수집 | Rsyslog, Loki Stack | 중앙 집중식 로그 수집 |
| 시각화 | Grafana | 실시간 로그 분석 및 대시보드 |
| 로그 포워딩 | Promtail | 로그 배달 에이전트 |

### 웹 서비스
| 항목 | 기술 | 설명 |
|------|------|------|
| 웹 서버 | Apache (HTTPD) 2.4.57 | 웹 서비스 제공 |
| 백엔드 | PHP, Node.js | 웹 애플리케이션 개발 |
| 프론트엔드 | HTML5, CSS3, JavaScript | 반응형 웹 디자인 |

---

## 시스템 아키텍처

### 네트워크 구성도

```
Region_A (DB Master & Primary Collector)
├── Server A: 웹 서비스 구축 사용
├── Server B: 주기 서버 및 백업용
├── Server C: 주기 서버 및 백업용
└── Node 2: DB Server (MariaDB Master)
    ├── Fail2Ban: 자동 차단 경비원
    ├── Firewalld: 포트 제한
    └── SSH/SFTP: 무단 접속 차단

            (Tailscale VPN Tunnel)
            
Region_B (Central Management & Analysis)
├── Node 1: Main Server
│   ├── Loki: 로그 스토리지
│   ├── Promtail: 로그 포워딩
│   └── rsyslog: 중앙 로그 수집
└── NFS Mount: 실시간 데이터 공유

            (SQL Query)
            
Region_C (Web & Visualization)
├── Grafana: 대시보드 및 실시간 알림
├── Web Server: 어울림 보안센터 웹사이트
└── Data Query: 분석 결과 시각화
```

### VLAN & 접근 제어

| 구분 | 인터넷 접근 | 내부망 접근 | 대상 | VLAN |
|------|----------|----------|------|------|
| 개발팀 | 차단 | NFS 스토리지 외 접근 불가 | - | VLAN30 (DEV) |
| 관제팀 | 허용 (업데이트/외부망) | 모든 서버군 SSH 접근 허용 | - | VLAN20 (NOC) |

---

## 주요 기능 및 구현 내용

### 인프라 및 네트워크 (In-Scope)
- VLAN 기반 내부 네트워크 분리 및 설계
- OSPF 활용 동적 라우팅 구성
- ACL(Access Control List) 접근 제어 정책 적용
- VPN을 통한 외부 사용자 보안 접속 환경 구축
- HSRP를 이용한 이중화 및 장애 조치

### 시스템 및 서비스 (In-Scope)
- Linux Web(Apache) & DB(MySQL/MariaDB) 구축
- 웹 기능 구현 (회원가입, 로그인, 게시판, 실시간 채팅)
- DNS 서버 및 도메인 기반 접속 환경 구성
- SSH / FTP 기반 원격 관리 환경 구축
- Master-Slave Replication을 통한 데이터 고가용성

### 보안 및 로그 관리 (In-Scope)
- firewalld 활용 서버 방화벽 설정 및 최적화
- 웹/시스템 로그 수집 및 분석 시스템 구축 (시각화 포함)
- 이상 행위 탐지 기준 설정
  - 로그인 실패 감시
  - 비정상 접근 탐지
  - Fail2Ban 자동 차단
- Loki + Grafana를 통한 실시간 대시보드
- Promtail을 통한 로그 배달 자동화

### 침해사고 대응 (In-Scope)
- 주요 공격 시나리오 구성
  - SQL Injection 탐지
  - Brute Force 공격 자동 차단
  - 비정상 접근 차단
- Fail2Ban을 활용한 공격 자동 탐지 및 차단
- 로그 기반 침해 대응 프로세스 설계
- 침해 대응 시연 및 검증

---

## 로그 흐름도

```
Fail2Ban 로그 발생
        |
        v
rsyslog (로컬)
        |
        v
파일 저장 (/var/log/fail2ban.log)
        |
        v
NFS를 통해 중앙 서버로 전송
        |
        v
Promtail이 로그 감지
        |
        v
Loki 스토리지에 저장
        |
        v
Grafana 대시보드에 실시간 표시
        |
        v
MariaDB에 구조화된 데이터 저장
```

---

## 보안 강화 사항

### Fail2Ban 설정
```bash
# SSH 비밀번호 3회 실패 -> 10분 차단
maxretry = 3
bantime = 600
findtime = 300
```

### SELinux 강제 접근 제어
```bash
# Enforcing Mode 활성화
sudo getenforce
# 결과: Enforcing
```

### Firewall 규칙
```bash
# Tailscale VPN 인터페이스를 신뢰 구역으로 설정
sudo firewall-cmd --permanent --zone=trusted --add-interface=tailscale0

# 데이터베이스 포트(3306) 허용
sudo firewall-cmd --permanent --add-port=3306/tcp

# 로그 수집 포트(514/UDP) 허용
sudo firewall-cmd --permanent --add-port=514/udp
```

---

## 웹 애플리케이션 아키텍처

### 웹 로직 흐름

<p align="center">
  <img src="https://github.com/user-attachments/assets/64006963-874c-4541-acc0-efc64becd5b2" width="100%" title="WBS Project Plan" alt="WBS Project Plan">
</p>

```
사용자 입장
    |
    v
index.html (홈페이지)
    |
    v
+---------------------------------------------+
|      로그인 / 회원가입 처리                  |
|  (login_check.php <-> logout.php)          |
|  (register_form.php -> register.php)       |
+---------------------------------------------+
    |
    v (인증 성공)
+---------------------------------------------+
|        메인 대시보드 (index.php)            |
|  ├── board.php (게시판 관리)                |
|  ├── chat_data (실시간 채팅)               |
|  ├── config.php (설정 파일)                |
|  └── security_logs.php (보안 로그)         |
+---------------------------------------------+
    |
    v
+---------------------------------------------+
|       백엔드 서비스 (Node.js)               |
|  ├── server.js (API 서버)                  |
|  ├── db.js (데이터베이스 연동)              |
|  └── chat_handler.php (실시간 통신)        |
+---------------------------------------------+
    |
    v
+---------------------------------------------+
|        저장소 (NFS Mount)                   |
|  ├── chat_eoulrim.json (채팅 데이터)       |
|  ├── chat_data/ (공유 저장소)              |
|  └── fail2ban.log (보안 로그)              |
+---------------------------------------------+
```

### 주요 PHP 모듈
| 파일명 | 역할 |
|--------|------|
| login_check.php | 사용자 인증 검증 |
| logout.php | 세션 종료 처리 |
| board.php | 게시판 CRUD 관리 |
| chat_handler.php | 실시간 채팅 통신 |
| security_logs.php | 보안 로그 조회 |
| web_access_monitor.php | 웹 접근 모니터링 |
| fetch_logs.php | Loki에서 로그 조회 |
| communication.php | API 통신 모듈 |

---

## 프로젝트 일정 (WBS)

### 주요 마일스톤

| 단계 | 업무 | 시작일 | 종료일 | 진행률 |
|------|------|--------|--------|--------|
| 1차 | 기획 / 주제 선정 | 2026.04.13 | 2026.04.13 | 100% |
| 2차 | 설계 / 아키텍처 구성 | 2026.04.13 | 2026.04.14 | 100% |
| 3차 | 구현 / 서버 구축 | 2026.04.13 | 2026.04.23 | 90% |
| 4차 | 프론트엔드 / 백엔드 설계 | 2026.04.13 | 2026.04.23 | 50% |
| 5차 | 네트워크 설계 | 2026.04.13 | 2026.04.23 | 50% |
| 6차 | 테스트 / 검증 | 2026.04.13 | 2026.04.23 | 0% |
| 7차 | 계획서 및 발표 자료 작성 | 2026.04.13 | 2026.04.23 | 30% |

---

## 핵심 시나리오 및 시연 포인트

### 1. 공격 탐지 및 차단 시연
```bash
# 공격자: 잘못된 비밀번호로 SSH 접속 시도 (3회)
ssh user@server-address
# 비밀번호 입력 실패 x 3회
# -> Fail2Ban이 공격자 IP를 자동 차단

# 관제팀: 차단된 IP 확인
sudo fail2ban-client status sshd
# 결과: Banned IP list: [공격자 IP]
```

### 2. 로그 실시간 모니터링
```bash
# 중앙 서버에서 실시간 로그 확인
tail -f /mnt/logs/fail2ban.log

# Grafana에서 쿼리로 특정 IP 추적
{job="fail2ban"} |= "[IP_ADDRESS]"
```

### 3. 데이터베이스 이중화 검증
```bash
# DB 마스터에서 데이터 입력
mysql> INSERT INTO fail2ban_logs VALUES (...);

# 중앙 서버에서 NFS 마운트로 즉시 동기화 확인
ls -la /mnt/logs/
```

---

## Out-of-Scope (범위 외)

- 실제 상용 클라우드(AWS, Azure 등) 인프라 연동
- 대규모 트래픽 처리 및 부하 성능 테스트
- 실제 결제 시스템 및 외부 상용 API 연동
- 상용 보안 장비(IPS, WAF 등) 도입
- AI(인공지능) 기반 이상 탐지 시스템
- 모바일 앱 버전 개발

---

## 접속 정보

### 데이터베이스 마스터
```
주소: [SERVER_ADDRESS]
포트: 3306
DB명: eoulrim_db
```

### 로그 수집 서버
```
주소: [LOG_SERVER_ADDRESS]
포트: 514 / UDP (Rsyslog)
```

### Grafana 대시보드
```
URL: http://[GRAFANA_ADDRESS]:3000
기본 계정: admin / admin
```

### Loki API
```
URL: http://[LOKI_ADDRESS]:3100
Query: {job="fail2ban"} |= "Ban"
```

---

## 팀 구성

| 역할 | 담당 영역 |
|------|-----------|
| 팀장 | 네트워크 설계, 전체 조율 |
| 인프라 | 서버 구축, DB 관리 |
| 개발 | 웹 개발, 프론트엔드 |
| 보안 | 보안 설정, 로그 분석 |

---

## 참고 자료

- Cisco Packet Tracer: 네트워크 시뮬레이션
- Rocky Linux 9.7: 보안 강화형 리눅스
- Grafana + Loki: 실시간 로그 분석
- Fail2Ban: 자동 차단 시스템

---

## 라이선스

이 프로젝트는 교육 목적으로 개발되었습니다.

---

마지막 업데이트: 2026.04.13 <br>
프로젝트 상태: 진행 중 <br>
작성자: palantir1997 <br>
