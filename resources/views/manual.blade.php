<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocTrax User Manual | Official Documentation</title>
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
            --accent-purple: #8b5cf6;
            --border: #e2e8f0;
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
            z-index: 100;
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
            border-bottom: 1px solid var(--border);
            background: white;
        }

        section:nth-child(even) { background: #fcfdfe; }
        .container { max-width: 1000px; margin: 0 auto; }

        h1, h2, h3 { font-family: 'Poppins', sans-serif; }
        h1 { font-size: 48px; margin-bottom: 10px; }
        h2 { font-size: 32px; color: #0f172a; margin-bottom: 30px; border-bottom: 2px solid var(--primary); display: inline-block; padding-bottom: 10px; }
        h3 { font-size: 22px; color: var(--primary); margin-top: 30px; margin-bottom: 15px; }

        p { line-height: 1.8; font-size: 16px; color: var(--text-muted); }

        /* FLOWCHART STYLES */
        .flow-wrapper {
            background: #f1f5f9;
            padding: 40px 20px;
            border-radius: 20px;
            margin: 30px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .flow-row {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .flow-node {
            background: white;
            border: 2px solid var(--primary);
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 140px;
            font-size: 13px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }
        .flow-node:hover { transform: translateY(-2px); }
        .flow-node.highlight { border-color: var(--accent-green); background: #f0fdf4; }
        .flow-arrow { color: #94a3b8; font-size: 18px; }

        /* ENTITY CARDS */
        .entity-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin: 25px 0; }
        .entity-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
        .entity-card h4 { margin: 0 0 10px 0; color: #0f172a; font-size: 16px; }
        .entity-card p { font-size: 13px; margin: 0; line-height: 1.5; }

        /* SCREENSHOT STYLES */
        .screenshot-placeholder {
            width: 100%;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            margin: 25px 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            text-align: center;
        }
        .screenshot-placeholder i { font-size: 40px; margin-bottom: 12px; }
        .screenshot-placeholder img { 
            max-width: 100%; 
            height: auto; 
            border-radius: 8px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            margin-top: 15px;
        }
        .img-caption { 
            font-size: 12px; 
            margin-top: 10px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }

        /* ACCORDION STYLES */
        .how-to-section { margin: 40px 0; }
        .how-to-header { font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .how-to-header i { color: var(--primary); }

        details {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        details[open] { border-color: var(--primary); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1); }
        
        summary {
            padding: 16px 20px;
            cursor: pointer;
            font-weight: 600;
            list-style: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            user-select: none;
        }
        summary::after {
            content: '\f078';
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 12px;
            transition: transform 0.3s;
        }
        details[open] summary::after { transform: rotate(180deg); }
        summary:hover { background: #f8fafc; }

        .details-content {
            padding: 20px;
            border-top: 1px solid var(--border);
            background: #fafafa;
        }
        .step-list { list-style: none; padding: 0; margin: 0; }
        .step-item { display: flex; gap: 15px; margin-bottom: 15px; }
        .step-num { 
            background: var(--primary); 
            color: white; 
            width: 24px; 
            height: 24px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 12px; 
            font-weight: 700; 
            flex-shrink: 0;
            margin-top: 2px;
        }
        .step-text { font-size: 14px; line-height: 1.6; color: var(--text-main); }
        .step-text strong { color: #0f172a; }

        /* GRID & LAYOUT */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
        .card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
            border-top: 5px solid var(--primary);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            background: #dbeafe;
            color: var(--primary);
            margin-bottom: 10px;
        }

        /* HERO SECTION */
        .hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
            color: white;
            padding: 100px 10%;
            text-align: center;
        }
        .hero h1 { color: white; margin-top: 0; }
        .hero p { color: #94a3b8; font-size: 18px; max-width: 600px; margin: 0 auto; }

        @media (max-width: 1024px) {
            nav { width: 0; padding: 0; display: none; }
            main { margin-left: 0; width: 100%; }
            .grid-2 { grid-template-columns: 1fr; }
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
            <li><a href="#accommodation">External Requests</a></li>
            <li><a href="#routing">Internal Routing</a></li>
            <li><a href="#admin">Administration</a></li>
        </ul>
    </nav>

    <main>
        <header class="hero" id="welcome">
            <div class="container">
                <span class="badge" style="background: rgba(255,255,255,0.1); color: white;">Documentation v1.0</span>
                <h1>DocTrax User Manual</h1>
                <p>Strategic Document Management: Bridging External Communications and Internal Workflows for DICT Region 2.</p>
            </div>
        </header>

        <!-- MODULE 1: EXTERNAL REQUESTS -->
        <section id="accommodation">
            <div class="container">
                <span class="badge">Module 01</span>
                <h2>External Requests Accommodation</h2>
                <p>This module is designed to receive, log, evaluate, endorse, assign, track, and complete external requests submitted to the organization through official channels such as physical letters, emails, or online submissions.</p>

                <div class="screenshot-placeholder">
                    <i class="fa-solid fa-laptop-code"></i>
                    <div class="img-caption">External Requests Dashboard View</div>
                    <img src="{{ asset('/manual-assets/1.png') }}" alt="External Monitoring Dashboard Screenshot">
                </div>

                <h3>Detailed How-to's</h3>
                <div class="how-to-section">
                    <details>
                        <summary>1. Initiate & Log an External Request (Receivers)</summary>
                        <div class="details-content">
                            <ul class="step-list">
                                <li class="step-item">
                                    <div class="step-num">1</div>
                                    <div class="step-text">
                                        Initiate a new external request from the sidebar using the <strong>+ External Request</strong> button, or from the dashboard under the <strong>External Requests</strong> section by clicking <strong>+ Initiate External Request</strong>.
                                    </div>
                                </li>

                                <li class="step-item">
                                    <div class="step-num">2</div>
                                    <div class="step-text">
                                        Enter the <strong>Request Details</strong> and <strong>Partner Agency Information</strong>, including the agency name, contact person, and official email address.
                                    </div>
                                </li>

                                <li class="step-item">
                                    <div class="step-num">3</div>
                                    <div class="step-text">
                                        (Optional but recommended) Provide the <strong>document reference number</strong>, set the <strong>priority level</strong>, and specify <strong>target dates</strong>.
                                    </div>
                                </li>

                                <li class="step-item">
                                    <div class="step-num">4</div>
                                    <div class="step-text">
                                        Attach a scanned copy of the request letter. Additional supporting files may also be attached in the following formats:
                                        <strong>xls, xlsx, pdf, png, jpg, jpeg, heic, txt, doc, docx, ppt, pptx</strong>.
                                    </div>
                                </li>

                                <li class="step-item">
                                    <div class="step-num">5</div>
                                    <div class="step-text">
                                        Endorse the request to specific division.
                                    </div>
                                </li>
                            </ul>

                            <p class="mt-3 text-sm text-slate-600">
                                <strong>Note:</strong> Users with <strong>Receiver</strong> access may monitor and track the
                                progress of submitted requests through the <strong>Monitoring</strong> section in the sidebar.
                            </p>
                        </div>
                    </details>

                    <details>
                        <summary>2. Action & Completion (Assigned Staff)</summary>
                        <div class="details-content">
                            <ul class="step-list">
                                <li class="step-item">
                                    <div class="step-num">1</div>
                                    <div class="step-text">From the <strong>"Assigned to Me"</strong> section in the sidebar.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">2</div>
                                    <div class="step-text">Perform the required technical/administrative action.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">3</div>
                                    <div class="step-text">Click <strong>Add Update</strong> and/or upload files using <strong>Attach Files</strong> (Photos, Reports, or Reply Letters).</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">4</div>
                                    <div class="step-text">Click <strong>Mark as Completed</strong> or <strong>Delegate to Colleague</strong> if further action is needed.</div>
                                </li>
                            </ul>
                        </div>
                    </details>
                </div>

                <h3>Core Entities</h3>
                <div class="entity-grid">
                    <div class="entity-card">
                        <h4>External Partner Agency</h4>
                        <p>Organizations outside DICT R2 formally submitting requests, projects, or services in writing/email.</p>
                    </div>
                    <div class="entity-card">
                        <h4>Receivers</h4>
                        <p>Designated personnel authorized to review, validate, and log incoming requests from the public.</p>
                    </div>
                    <div class="entity-card">
                        <h4>Assigned Personnel</h4>
                        <p>The "Action Officers" responsible for executing the task, logging updates, and uploading proof of action.</p>
                    </div>
                    <div class="entity-card" style="border-left-color: var(--accent-purple);">
                        <h4>Monitorers</h4>
                        <p>Global oversight personnel who track requests in any status and provide collaborative updates or attachments.</p>
                    </div>
                </div>

                <h3>External Workflow Flowchart</h3>
                <div class="flow-wrapper">
                    <div class="flow-row">
                        <div class="flow-node">Partner Agency<br><small>Sends Mail/Email</small></div>
                        <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="flow-node">Receiver<br><small>Validate & Log</small></div>
                        <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                    </div>
                    <div class="flow-arrow"><i class="fa-solid fa-arrow-down"></i></div>
                    <div class="flow-row">
                        <div class="flow-node">Division Chief<br><small>Accept/Delegate</small></div>
                        <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="flow-node highlight">Assigned Personnel<br><small>Action & Update</small></div>
                        <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="flow-node">Completion<br><small>Final Archive</small></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- MODULE 2: INTERNAL ROUTING -->
        <section id="routing">
            <div class="container">
                <span class="badge">Module 02</span>
                <h2>Internal Routing</h2>
                <p>Controlled routing of internal documents (Memorandums, Travel Orders, etc.) within the organization, from management to units, ensuring a transparent review and signing process.</p>

                <div class="screenshot-placeholder">
                    <i class="fa-solid fa-file-invoice"></i>
                    <div class="img-caption">Internal Routing List View</div>
                    <img src="{{ asset('/manual-assets/2.png') }}" alt="Internal Routing Screenshot">
                </div>

                <h3>Detailed How-to's</h3>
                <div class="how-to-section">
                    <details>
                        <summary>1. Initiating a Routing (Originators)</summary>
                        <div class="details-content">
                            <ul class="step-list">
                                <li class="step-item">
                                    <div class="step-num">1</div>
                                    <div class="step-text">Click <strong>New Internal Routing</strong> from the sidebar. Alternatively, you may use the <strong>+ Initiate New Internal Routing</strong> button in the main dashboard's <strong>Routing Snapshot</strong> section.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">2</div>
                                    <div class="step-text">Type in the routing details, description, priority level, and any other relevant information.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">3</div>
                                    <div class="step-text">Define the <strong>Workflow Sequence</strong>. Add reviewers in the exact order they need to see the document.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">4</div>
                                    <div class="step-text">Upload the <strong>Primary Document</strong> (PDF for signing) and any <strong>Supporting Files</strong>.</div>
                                </li>
                                
                                <li class="step-item">
                                    <div class="step-num">5</div>
                                    <div class="step-text">Click <strong>Launch</strong>. The document will immediately move to Reviewer #1.</div>
                                </li>
                            </ul>
                        </div>
                    </details>

                    <details>
                        <summary>2. Reviewing, Signing & Returning (Reviewers - Any Staff)</summary>
                        <div class="details-content">
                            <ul class="step-list">
                                <li class="step-item">
                                    <div class="step-num">1</div>
                                    <div class="step-text">When a document arrives in your <strong>For Signing</strong>, open it to review attachments.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">2</div>
                                    <div class="step-text"><strong>To Forward:</strong> Add your comment and click <strong>Forward</strong>. It moves to the next reviewer in sequence.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">3</div>
                                    <div class="step-text"><strong>To Return:</strong> If revisions are needed, click <strong>Return</strong>. You must provide a reason.</div>
                                </li>
                                <li class="step-item">
                                    <div class="step-num">4</div>
                                    <div class="step-text"><strong>To Reject:</strong> If the request is invalid, click <strong>Reject</strong>. This terminates the routing for all participants.</div>
                                </li>
                            </ul>
                        </div>
                    </details>
                </div>

                <div class="grid-2">
                    <div class="card">
                        <h3>Reviewer Actions</h3>
                        <p style="font-size: 14px;">Reviewers have three key options when a document arrives:</p>
                        <div style="display:flex; flex-direction:column; gap:10px; margin-top:15px;">
                            <span style="color: var(--accent-green); font-weight:700;"><i class="fa-solid fa-check-double"></i> Sign & Forward</span>
                            <span style="color: #f59e0b; font-weight:700;"><i class="fa-solid fa-rotate-left"></i> Return for Revision</span>
                            <span style="color: #ef4444; font-weight:700;"><i class="fa-solid fa-ban"></i> Reject (Void Request)</span>
                        </div>
                    </div>
                    <div class="card" style="border-top-color: var(--accent-purple);">
                        <h3>Originator Options</h3>
                        <p style="font-size: 14px;">Originators track the document in real-time:</p>
                        <ul style="font-size: 13px; color: var(--text-muted); padding-left: 15px;">
                            <li><strong>Monitor:</strong> See exactly who is currently holding the document.</li>
                            <li><strong>Resubmit:</strong> If returned, fix the issue and send it back into the workflow.</li>
                        </ul>
                    </div>
                </div>

                <h3>Internal Routing Flowchart</h3>
                <div class="flow-wrapper">
                    <div class="flow-row">
                        <div class="flow-node">Originator<br><small>Create & Attach</small></div>
                        <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="flow-node">Reviewer 1<br><small>Process & Sign</small></div>
                        <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="flow-node">Reviewer ...n<br><small>Process & Sign</small></div>
                        <div class="flow-arrow"><i class="fa-solid fa-arrow-right"></i></div>
                        <div class="flow-node highlight">Last Reviewer<br><small>Mark Complete</small></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ADMINISTRATION -->
        <section id="admin">
            <div class="container">
                <h2>System Administration</h2>
                <p>Maintaining the structural integrity and security of DocTrax.</p>
                
                <div class="grid-2" style="margin-top: 30px;">
                    <div class="card">
                        <h3><i class="fa-solid fa-users-gear"></i> User Management</h3>
                        <p>Admins handle the creation of accounts and the assignment of specific roles (Receiver, Monitorer, etc.) to ensure the chain of command is maintained.</p>
                    </div>
                    <div class="card" style="border-top-color: var(--accent-green);">
                        <h3><i class="fa-solid fa-sitemap"></i> Division Scopes</h3>
                        <p>Defining which personnel belong to AFD or TOD ensures that "Endorsers" can route external requests to the correct Division Chiefs.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer style="background: #0f172a; color: #94a3b8; padding: 60px 10%; text-align: center;">
            <p style="color: white; font-weight: 600;">DocTrax v1.1 | DICT Region 2</p>
            <p>Empowering the region through digital document efficiency.</p>
            <div style="margin-top: 20px; font-size: 12px; line-height: 1.6;">
                Prepared by: <strong>Karl Steven A. Maddela, PDO III</strong><br>
                DICT Region 2 - Systems Development Team
            </div>
        </footer>
    </main>

    <script>
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section, header');
            const navLinks = document.querySelectorAll('nav a');
            
            let current = "";
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
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