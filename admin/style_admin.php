body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; margin: 0; display: flex; }
.sidebar { width: 260px; background: #2c3e50; height: 100vh; position: fixed; left: 0; top: 0; padding: 30px 0; color: white; }
.sidebar h3 { text-align: center; margin-bottom: 40px; color: #3498db; }
.sidebar a { display: block; color: #bdc3c7; text-decoration: none; padding: 15px 25px; transition: 0.3s; border-left: 4px solid transparent; }
.sidebar a:hover, .sidebar a.active { background: #34495e; color: white; border-left: 4px solid #3498db; }
.main-content { flex: 1; margin-left: 260px; padding: 40px; }