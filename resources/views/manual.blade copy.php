<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocTrax User Manual | Documentation</title>
    <link rel="icon" type="image/png" href="/logo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #1e40af;
            --bg: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --sidebar-w: 280px;
            --accent-green: #10b981;
        }

        * { box-sizing: border-box; scroll-behavior: smooth; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-main); display: flex; }

        /* SIDEBAR NAVIGATION */
        nav {
            width: var(--sidebar-w);
            height: 100vh;
            background: #0f172a;
            color: white;
            position: fixed;
            padding: 40px 20px;
            overflow-y: auto;
        }

        .nav-logo {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
        }

        nav ul { list-style: none; padding: 0; margin: 0; }
        nav li { margin-bottom: 5px; }
        nav a {
            color: #94a3b8;
            text-decoration: none;
            padding: 12px 15px;
            display: block;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }
        nav a:hover { background: rgba(255,255,255,0.05); color: white; }
        nav a.active { background: var(--primary); color: white; }

        /* MAIN CONTENT */
        main {
            margin-left: var(--sidebar-w);
            width: calc(100% - var(--sidebar-w));
            padding: 0;
        }

        section {
            padding: 80px 10%;
            border-bottom: 1px solid #e2e8f0;
            background: white;
        }

        section:nth-child(even) { background: #fcfdfe; }

        .container { max-width: 1000px; margin: 0 auto; }

        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
        h1 { font-size: 48px; margin-bottom: 20px; }
        h2 { font-size: 32px; color: #0f172a; margin-bottom: 30px; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 10px; }
        h3 { font-size: 22px; color: var(--primary); margin-top: 30px; }

        p { line-height: 1.8; font-size: 16px; color: var(--text-muted); }

        /* FLOWCHART STYLES */
        .flow-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 15px;
            background: #f1f5f9;
            padding: 40px;
            border-radius: 16px;
            margin: 30px 0;
        }
        .flow-node {
            background: white;
            border: 2px solid var(--primary);
            padding: 15px 20px;
            border-radius: 10px;
            font-weight: 600;
            text-align: center;
            min-width: 160px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .flow-node.highlight { border-color: var(--accent-green); background: #f0fdf4; }
        .flow-arrow { color: #94a3b8; font-size: 20px; }

        /* SCREENSHOT PLACEHOLDER */
        .screenshot-placeholder {
            width: 100%;
            height: 400px;
            background: #f8fafc;
            border: 3px dashed #cbd5e1;
            border-radius: 12px;
            margin: 25px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            text-align: center;
        }
        .screenshot-placeholder i { font-size: 48px; margin-bottom: 15px; }

        /* TILES & GRIDS */
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px; }
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            border-top: 4px solid var(--primary);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }

        /* TABLE */
        .table-wrap { overflow-x: auto; margin: 30px 0; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; background: white; }
        th { background: #0f172a; color: white; text-align: left; padding: 18px; }
        td { padding: 18px; border-bottom: 1px solid #f1f5f9; }

        /* BUTTONS/UI ELEMENTS */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            background: #dbeafe;
            color: var(--primary);
            margin-bottom: 10px;
        }

        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
            color: white;
            padding: 120px 10%;
            text-align: center;
        }
        .hero h1 { color: white; margin-top: 0; }
        .hero p { color: #94a3b8; font-size: 20px; }

        @media (max-width: 900px) {
            nav { width: 0; padding: 0; display: none; }
            main { margin-left: 0; width: 100%; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav>
        <div class="nav-logo">
            <i class="fa-solid fa-file-signature"></i> DocTrax
        </div>
        <ul>
            <li><a href="#welcome" class="active">Welcome</a></li>
            <li><a href="#overview">System Overview</a></li>
            <li><a href="#external-workflow">External Workflow</a></li>
            <li><a href="#internal-routing">Internal Routing</a></li>
            <li><a href="#permissions">Role Permissions</a></li>
            <li><a href="#admin">Administration</a></li>
        </ul>
    </nav>

    <main>
        <header class="hero" id="welcome">
            <div class="container">
                <span class="badge" style="background: rgba(255,255,255,0.1); color: white;">Documentation v1.0</span>
                <h1>DocTrax User Manual</h1>
                <p>Digital Document Routing and Strategic Request Tracking System</p>
            </div>
        </header>

        <!-- SYSTEM OVERVIEW -->
        <section id="overview">
            <div class="container">
                <h2>System Overview</h2>
                <p>DocTrax is a dual-purpose platform built on Laravel, designed to digitize the flow of documents and external communications within the regional office.</p>
                
                <div class="grid">
                    <div class="card">
                        <i class="fa-solid fa-file-signature" style="font-size: 30px; color: var(--primary); margin-bottom: 15px; display: block;"></i>
                        <h3>Internal Routing</h3>
                        <p>Streamlines the process of getting documents signed. Supports sequential review, return-for-revision, and final archival.</p>
                    </div>
                    <div class="card" style="border-top-color: var(--accent-green);">
                        <i class="fa-solid fa-envelope-open-text" style="font-size: 30px; color: var(--accent-green); margin-bottom: 15px; display: block;"></i>
                        <h3>External Requests</h3>
                        <p>Tracks mail and email from partner agencies. Manages the lifecycle from the Records Officer to the Action Officer.</p>
                    </div>
                </div>

                <div class="screenshot-placeholder">
                    <i class="fa-solid fa-desktop"></i>
                    <p>PLACE SYSTEM DASHBOARD SCREENSHOT HERE</p>
                </div>
            </div>
        </section>

        <!-- EXTERNAL WORKFLOW -->
        <section id="external-workflow">
            <div class="container">
                <h2>External Request Workflow</h2>
                <p>Follow the chain of command for incoming communications from partner agencies.</p>
                
                <div class="flow-container">
                    <div class="flow-node">1. Records<br><small>Logs Request</small></div>
                    <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flow-node">2. Director<br><small>Endorses Division</small></div>
                    <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flow-node">3. Division Chief<br><small>Assigns Personnel</small></div>
                    <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flow-node highlight">4. Personnel<br><small>Take Action</small></div>
                </div>

                <h3>Step-by-Step Instructions</h3>
                <div class="grid">
                    <div>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 20px;">
                                <strong><i class="fa-solid fa-1" style="color: var(--primary); margin-right: 10px;"></i> Recording:</strong> 
                                Navigate to <code>Externals > Create</code>. Input the partner agency name, subject, and date received.
                            </li>
                            <li style="margin-bottom: 20px;">
                                <strong><i class="fa-solid fa-2" style="color: var(--primary); margin-right: 10px;"></i> Endorsing:</strong> 
                                The Director's office reviews the "Endorsing" tab to forward the task to Admin/Technical divisions.
                            </li>
                        </ul>
                    </div>
                    <div>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 20px;">
                                <strong><i class="fa-solid fa-3" style="color: var(--primary); margin-right: 10px;"></i> Acting:</strong> 
                                Personnel find tasks in "My Tasks". They can add updates, upload proof of completion, or forward to others if needed.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="screenshot-placeholder">
                    <i class="fa-solid fa-inbox"></i>
                    <p>PLACE EXTERNAL MONITORING TAB SCREENSHOT HERE</p>
                </div>
            </div>
        </section>

        <!-- INTERNAL ROUTING -->
        <section id="internal-routing">
            <div class="container">
                <h2>Internal Document Routing</h2>
                <p>Sequential review logic for internal documents like Travel Orders or Memorandums.</p>

                <div class="flow-container">
                    <div class="flow-node">Originator<br><small>Staff</small></div>
                    <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flow-node">Reviewer A<br><small>Sign & Forward</small></div>
                    <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flow-node">Reviewer B<br><small>Sign & Forward</small></div>
                    <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                    <div class="flow-node highlight">Complete</div>
                </div>

                <div class="grid">
                    <div class="card">
                        <h3>Critical Action: Signing</h3>
                        <p>When a document reaches you:</p>
                        <ol style="color: var(--text-muted); padding-left: 20px;">
                            <li>Download the current attachment.</li>
                            <li>Open in your PDF reader and apply your signature.</li>
                            <li><strong>Crucial:</strong> Use the "Replace Attachment" button in DocTrax to upload the signed version.</li>
                            <li>Click "Process" to send to the next node.</li>
                        </ol>
                    </div>
                    <div class="screenshot-placeholder" style="height: 300px;">
                        <i class="fa-solid fa-file-pdf"></i>
                        <p>PLACE SCREENSHOT OF<br>FILE ACTION BUTTONS HERE</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PERMISSIONS -->
        <section id="permissions">
            <div class="container">
                <h2>Role Permissions Matrix</h2>
                <p>The system dynamically adapts the sidebar based on your assigned role.</p>
                
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Externals</th>
                                <th>Internals</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Records Officer</strong></td>
                                <td>Full Control (Logging)</td>
                                <td>Originator</td>
                                <td>Read-Only</td>
                            </tr>
                            <tr>
                                <td><strong>Director</strong></td>
                                <td>Endorse & Monitor</td>
                                <td>Final Signatory</td>
                                <td>Update Leaders</td>
                            </tr>
                            <tr>
                                <td><strong>Division Chief</strong></td>
                                <td>Assign Personnel</td>
                                <td>Reviewer</td>
                                <td>View Setup</td>
                            </tr>
                            <tr>
                                <td><strong>Action Officer</strong></td>
                                <td>Add Updates/Proofs</td>
                                <td>Originator</td>
                                <td>Profile Only</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- ADMINISTRATION -->
        <section id="admin" style="padding-bottom: 120px;">
            <div class="container">
                <h2>System Administration</h2>
                <p>Managing the "Brain" of DocTrax.</p>
                
                <div class="grid">
                    <div>
                        <h3>Personnel & Leadership</h3>
                        <p>The system relies on a hierarchy. Administrators must ensure the "Leadership" settings accurately reflect the current occupants of the Director and Division Chief offices to ensure requests route correctly.</p>
                        <ul style="list-style: none; padding: 0; color: var(--text-muted);">
                            <li style="margin-bottom: 10px;"><i class="fa-solid fa-check" style="color: var(--primary);"></i> Add/Remove Users</li>
                            <li style="margin-bottom: 10px;"><i class="fa-solid fa-check" style="color: var(--primary);"></i> Configure Division Scopes</li>
                            <li style="margin-bottom: 10px;"><i class="fa-solid fa-check" style="color: var(--primary);"></i> Set System Preferences</li>
                        </ul>
                    </div>
                    <div class="screenshot-placeholder" style="height: 300px;">
                        <i class="fa-solid fa-gears"></i>
                        <p>PLACE SETTINGS TAB SCREENSHOT HERE</p>
                    </div>
                </div>
            </div>
        </section>

        <footer style="background: #0f172a; color: #94a3b8; padding: 60px 10%; text-align: center;">
            <p style="color: white; font-weight: 600;">DocTrax v1.0 | Official User Manual</p>
            <p>For technical support, please contact the ICT Support Unit.</p>
            <div style="margin-top: 20px;">
                <i class="fa-brands fa-laravel" style="font-size: 24px; margin-right: 15px;"></i>
                <i class="fa-solid fa-shield-halved" style="font-size: 24px;"></i>
            </div>
        </footer>
    </main>

    <script>
        // Simple active state tracking for nav
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section, header');
            const navLinks = document.querySelectorAll('nav a');
            
            let current = "";
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>