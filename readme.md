# 🛡️ Aulim Security - 웹 서비스 기업 대상 통합 보안 솔루션

## 📺 시연 영상
[▶ YouTube에서 보기](https://www.youtube.com/watch?v=quweHTW2XGM)
---

<p align="center">
  <img width="1904" height="929" alt="Image" src="https://github.com/user-attachments/assets/987787ac-310f-435e-ae57-83885f33cdf3" />
  <img width="1770" height="808" alt="Image" src="https://github.com/user-attachments/assets/fd2e65e3-f18a-4fd6-9266-2d16174811e2" />
</p>

웹 서비스 환경의 보안 위협에 대응할 수 있는 통합 보안 관제 시스템입니다. <br>
실시간 탐지 및 자동 대응이 가능한 안정적인 서비스 운영 환경을 구축합니다.

---

## 📋 프로젝트 개요

### 기본 정보
- **프로젝트명**: Aulim Security (어울림)
- **진행 기간**: 2026.04.13 ~ 2026.04.24
- **팀 구성**: 4명
- **카테고리**: 시스템 보안, 네트워크 보안, 로그 분석

### 선정 배경
최근 웹 서비스 기업을 대상으로 한 해킹 및 개인정보 유출 사고가 증가하고 있습니다. 이에 따라 다음과 같은 필요성이 대두되었습니다:
- 네트워크, 서버, 로그 분석을 포함한 통합적인 보안 체계의 필수성
- 실제 기업 환경을 가정한 공격 탐지 및 대응 시스템의 구축 필요
- 보안 관제 센터(SOC) 운영 모델의 실제 구현
- 기존 웹 서비스 운영 환경의 파편화된 로그 문제 해결 및 실시간 통합 분석 체계 구축

---

## 🎯 프로젝트 목표 및 기대 효과

### 최종 목표
웹 서비스 환경의 보안 위협을 실시간으로 탐지하고, 로그 기반 분석 및 자동 대응이 가능한 통합 보안 관제 시스템을 구축합니다.

### 예상 결과물

1. **네트워크 아키텍처**
   - Cisco Packet Tracer를 활용한 가상 네트워크 환경 구현
   - 실제 보안 관제 센터의 네트워크 구성과 데이터 흐름을 시각적으로 표현

2. **보안 관제 시스템**
   - 데이터베이스 이중화(Master-Slave Replication)
   - 안전한 파일 공유 시스템(NFS/SFTP)
   - 비즈니스 연속성 보장

3. **침해 대응 체계**
   - 실시간 로그 수집 및 분석
   - 자동 차단 및 알림 시스템
   - 침해 사고 신속 대응

### 기대 효과
- 보안 위협에 대한 실시간 탐지 및 자동 대응
- 내부 정보 유출 및 서비스 장애 사전 방지
- 침해 사고 발생 시 신속한 원인 파악
- 안정적인 서비스 운영 환경 확보

---

## ⚙️ 기술 스택 및 환경 구성

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

## 🏗️ 시스템 아키텍처

### 서버 구성도
<p align="center">
  <img src="https://github.com/user-attachments/assets/44641a93-8545-4944-9196-d9d664f09e05" width="100%">
</p>

```
Region_A (DB Master & Primary Collector)
├── Server A: 웹 서비스 구축 사용
├── Server B: 추가 서버 및 백업용
├── Server C: 추가 서버 및 백업용
└── Node 2: DB Server (MariaDB Master)
    ├── Fail2Ban: 자동 차단
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

> 모든 Region에서 **Firewall Enabled (Port Restricted)** / **SELinux: Enforcing Mode** 적용

---

### 네트워크 설계

<p align="center">
  <img src="https://github.com/user-attachments/assets/a900aaf6-117c-4054-834a-b1b06a06f361" width="100%">
   <img src="https://github.com/user-attachments/assets/1e84c1be-466d-4c3c-9e18-7c470a94c72f" width="100%">
</p>

---

## 🔧 주요 기능 및 구현 내용

### 인프라 및 네트워크
- VLAN 기반 내부 네트워크 분리 및 설계
- OSPF 활용 동적 라우팅 구성
- ACL(Access Control List) 접근 제어 정책 적용
- VPN을 통한 외부 사용자 보안 접속 환경 구축
- HSRP를 이용한 이중화 및 장애 조치

### 시스템 및 서비스
- Linux Web(Apache) & DB(MySQL/MariaDB) 구축
- 웹 기능 구현 (회원가입, 로그인, 게시판, 실시간 채팅)
- DNS 서버 및 도메인 기반 접속 환경 구성 (aulim-security.com)
- SSH / SFTP 기반 원격 관리 환경 구축
- Master-Slave Replication을 통한 데이터 고가용성

### 보안 및 로그 관리
- firewalld 활용 서버 방화벽 설정 및 최적화
- 웹/시스템 로그 수집 및 분석 시스템 구축 (시각화 포함)
- 이상 행위 탐지 기준 설정 (로그인 실패 감시 / 비정상 접근 탐지 / Fail2Ban 자동 차단)
- Loki + Grafana를 통한 실시간 대시보드
- Promtail을 통한 로그 배달 자동화

### 침해사고 대응
- 주요 공격 시나리오 구성 및 시연
  - SQL Injection 탐지
  - Brute Force 공격 자동 차단
  - XSS(크로스 사이트 스크립팅) 방어
  - 파일 업로드 취약점 차단 (.php 등 위험 확장자)
  - 비정상 접근 및 디렉토리 탐색 공격 탐지
  - DoS/DDoS 시뮬레이션 및 자동 방어 검증
- Fail2Ban을 활용한 공격 자동 탐지 및 차단
- 로그 기반 침해 대응 프로세스 설계 및 검증

---

## 📊 로그 흐름도

### 로직 구성도
<p align="center">
  <img src="https://github.com/user-attachments/assets/6b539b2d-c31d-466e-9dd5-7d20f438f137" width="100%">
</p>

> ※ rsyslog → MariaDB 저장 경로는 설계 완료, 사용하지 않고 있음.

---

## 🔐 보안 강화 사항

### Fail2Ban 설정
```bash
# SSH/SFTP 비밀번호 3회 실패 → 10분 차단
maxretry = 3
bantime = 600
findtime = 300
```

### SELinux 강제 접근 제어
```bash
# Enforcing Mode 활성화 확인
sudo getenforce
# 결과: Enforcing
```

### Firewall 규칙
```bash
# Tailscale VPN 인터페이스를 신뢰 구역으로 설정
sudo firewall-cmd --permanent --zone=trusted --add-interface=tailscale0

# 로그 수집 포트(514/UDP) 허용
sudo firewall-cmd --permanent --add-port=514/udp

sudo firewall-cmd --reload
```

---

## 🌐 웹 애플리케이션 아키텍처

### 웹 로직 흐름

<p align="center">
  <img src="https://github.com/user-attachments/assets/64006963-874c-4541-acc0-efc64becd5b2" width="100%" title="Web Application Flow" alt="Web Application Flow">
  <img src="https://github.com/user-attachments/assets/c470fa2b-6bf3-40b1-b841-144640e7e368" width="100%">
</p>

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
## 보안 테스트 및 에러 핸들링 결과
<p align="center">
  <img width="1749" height="963" alt="Image" src="https://github.com/user-attachments/assets/10b24c31-bace-4af9-9b3c-bd1b2c58b68a" />
  <img width="1740" height="968" alt="Image" src="https://github.com/user-attachments/assets/7a064d2b-bc43-4fc4-b257-d8a73726f762" />
  <img width="1736" height="945" alt="Image" src="https://github.com/user-attachments/assets/baa34400-1a40-4fb8-824e-c1d3c70c06e5" />
  <img width="1731" height="954" alt="Image" src="https://github.com/user-attachments/assets/67bc2d0a-11af-45f0-8590-419d6f7b79e9" />
  <img width="1745" height="961" alt="Image" src="https://github.com/user-attachments/assets/0fe9cf80-7560-4f74-8371-c5c0362f2ff9" />
  <img width="1739" height="965" alt="Image" src="https://github.com/user-attachments/assets/c762e632-6263-4cd4-844b-ff44ef6c8154" />
  <img width="1743" height="964" alt="Image" src="https://github.com/user-attachments/assets/7acbc710-c02c-4eb4-b6e9-787a4673d383" />
  <img width="1730" height="959" alt="Image" src="https://github.com/user-attachments/assets/f52e9a50-15b5-48fa-94ef-16773f57d9bd" />
  <img width="1738" height="955" alt="Image" src="https://github.com/user-attachments/assets/acc6ad71-dc1c-4bc2-a1e6-dd9cf531876f" />
  <img width="1737" height="963" alt="Image" src="https://github.com/user-attachments/assets/e311588f-e184-466d-ae4e-086c58a78f97" />
  <img width="1747" height="959" alt="Image" src="https://github.com/user-attachments/assets/9f4d1214-ce6d-461d-bda6-44aaa04eb656" />
  <img width="1741" height="958" alt="Image" src="https://github.com/user-attachments/assets/c8161a6f-b0db-41f9-9d8d-4d8bff146458" />
  <img width="1745" height="963" alt="Image" src="https://github.com/user-attachments/assets/beb94c46-0732-45e7-909b-714311760de0" />
  <img width="1735" height="969" alt="Image" src="https://github.com/user-attachments/assets/2d050bc8-c5a5-4b40-b271-fbb61d583b67" />
  <img width="1740" height="964" alt="Image" src="https://github.com/user-attachments/assets/94392a18-4f31-4cfc-abcb-35425802ad55" />
  <img width="1744" height="969" alt="Image" src="https://github.com/user-attachments/assets/19ec7d48-95b1-4c41-a1ec-06c033ea4b3b" />
</p>

---

## 📅 프로젝트 일정 (WBS)

| 단계 | 업무 | 시작일 | 종료일 | 진행률 |
|------|------|--------|--------|--------|
| 1차 | 기획 / 주제 선정 | 2026.04.13 | 2026.04.13 | 100% |
| 2차 | 설계 / 아키텍처 구성 | 2026.04.13 | 2026.04.14 | 100% |
| 3차 | 구현 / 서버 구축 | 2026.04.13 | 2026.04.23 | 100% |
| 4차 | 프론트엔드 / 백엔드 설계 | 2026.04.13 | 2026.04.23 | 100% |
| 5차 | 네트워크 설계 | 2026.04.13 | 2026.04.23 | 100% |
| 6차 | 테스트 / 검증 | 2026.04.13 | 2026.04.23 | 100% |
| 7차 | 계획서 및 발표 자료 작성 | 2026.04.13 | 2026.04.24 | 100% |

---

## 👥 팀 구성

| GitHub | 이름 | 담당 영역 |
|--------|------|-----------|
| palantir1997(팀장) | 서버 아키텍처 설계, VPN, Grafana/Loki 모니터링, 문서화(PPT/GitHub), 에러 핸들링 |
| Jeongjaew0n1 | 백엔드, DB 관리, DNS 구축, Master-Slave 복제, 에러 핸들링 |
| Hong-ki-su | 프론트엔드/UI 개발, 회원가입/로그인 API, 파일 업로드 보안, 에러 핸들링 |
| jungee-11 | 네트워크 설계(VLAN/OSPF/HSRP/VPN), 통신 테스트, 발표 대본 작성 |

---

## 📚 참고 자료

- [Cisco Packet Tracer](https://www.netacad.com/courses/packet-tracer) - 네트워크 시뮬레이션
- [Rocky Linux 9.7](https://rockylinux.org/) - 보안 강화형 리눅스
- [Grafana + Loki](https://grafana.com/oss/loki/) - 실시간 로그 분석
- [Fail2Ban](https://www.fail2ban.org/) - 자동 차단 시스템
- [Tailscale](https://tailscale.com/) - VPN 보안 터널

---

## 📄 라이선스

이 프로젝트는 **교육 목적**으로 개발되었습니다.

---

마지막 업데이트: 2026.04.22 <br>
프로젝트 상태: ✅ 완료 <br>
작성자: palantir1997
