<div class="container-fluid px-4">
            <h3 class="h4 mb-4 text-gray-800 fw-bold text-start">Admin Dashboard - Network Status</h3>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header py-3 bg-dark text-start">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-server me-2"></i>Server Status Monitor (Host-name based)</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped border-0 mb-0">
                        <thead class="table-light">
                            <tr><th>Host Name</th><th>Service / Role</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr><td class="fw-bold">WEB-SVR-01</td><td>Apache</td><td><span class="badge bg-success">OK</span></td></tr>
                            <tr><td class="fw-bold">DB-SVR-01</td><td>MariaDB/MySQL</td><td><span class="badge bg-success">OK</span></td></tr>
                            <tr><td class="fw-bold">GW-ROUTER</td><td>VPN / EIGRP</td><td><span class="badge bg-success">OK</span></td></tr>
                            <tr><td class="fw-bold">GRE Tunnel</td><td>Status</td><td><span class="badge bg-success">OK</span></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header py-3 bg-dark text-start">
                    <h6 class="m-0 fw-bold text-white"><i class="fas fa-users me-2"></i>My Active Session Info</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-striped border-0 mb-0">
                        <thead class="table-light">
                            <tr><th>Headers</th><th>Values</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold" style="width: 30%">접속 유저</td>
                                <td><?php echo $userId ? htmlspecialchars($userId) : 'Guest'; ?></td>
                                <td><span class="badge <?php echo $userId ? 'bg-success' : 'bg-secondary'; ?>"><?php echo $userId ? 'Logged In' : 'Guest'; ?></span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">접속 IP</td>
                                <td><?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']); ?></td>
                                <td><span class="badge bg-info">Connected</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">서버 호스트명</td>
                                <td><?php echo htmlspecialchars(gethostname()); ?></td>
                                <td><span class="badge bg-success">OK</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">접속 시각</td>
                                <td><?php echo date('Y-m-d H:i:s'); ?></td>
                                <td><span class="badge bg-primary">Now</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">브라우저</td>
                                <td><?php echo htmlspecialchars(substr($_SERVER['HTTP_USER_AGENT'], 0, 50)) . '...'; ?></td>
                                <td><span class="badge bg-warning text-dark">Client</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm mb-4 border-0 border-start border-primary border-5">
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><i class="fas fa-broadcast-tower me-2"></i>Live Team Sessions (Real-time Monitoring)</h6>
                    <span id="onlineCount" class="badge bg-primary">0명 접속 중</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User ID</th>
                                <th>Current Location</th>
                                <th>Client IP</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="onlineUserTable">
                            <tr><td colspan="4" class="text-center py-4 text-muted">데이터 동기화 중...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> </div> <script>
        // 현재 페이지 이름 설정
        const currentPageName = "Admin Dashboard";

        async function updateHeartbeat() {
            const formData = new FormData();
            formData.append('page', currentPageName);

            try {
                const res = await fetch('heartbeat.php', { method: 'POST', body: formData });
                const data = await res.json();
                if (data.success) { renderOnlineUsers(data.onlineUsers); }
            } catch (e) { console.error("Heartbeat sync error:", e); }
        }

        function renderOnlineUsers(users) {
            const tbody = document.getElementById('onlineUserTable');
            const countBadge = document.getElementById('onlineCount');
            countBadge.innerText = `${users.length}명 접속 중`;
            
            tbody.innerHTML = '';
            users.forEach(user => {
                const row = `
                    <tr>
                        <td class="fw-bold"><i class="fas fa-user-circle me-2 text-secondary"></i>${user.id}</td>
                        <td><span class="badge bg-light text-dark border px-2">${user.page}</span></td>
                        <td><small class="text-muted font-monospace">${user.ip}</small></td>
                        <td><span class="status-dot"></span><span class="text-success fw-bold" style="font-size: 0.85rem;">Active</span></td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        setInterval(updateHeartbeat, 5000);
        updateHeartbeat();
    </script>