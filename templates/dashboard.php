<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Carbon Intelligence - Domain WHOIS Lookup, Bulk Analysis & DNS Tools">
    <meta name="theme-color" content="#0F172A">
    <title>Carbon Intelligence</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="512x512" href="<?php echo $base; ?>assets/favicon.png">
    <link rel="apple-touch-icon" href="<?php echo $base; ?>assets/favicon.png">

    <link href="<?php echo $base; ?>assets/css/app.css" rel="stylesheet">
</head>

<body>

    <div class="app-layout">
        <aside class="sidebar">
            <div class="brand-unit">
                <div class="brand-icon"></div>
                <div class="brand-text">Carbon<span style="color:var(--text-light); font-weight:400;">_INTEL</span>
                </div>
            </div>

            <nav class="nav-menu">
                <a href="<?php echo $base; ?>" class="nav-item active" data-view="dashboard">
                    <svg class="nav-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Single Lookup
                </a>
                <a href="<?php echo $base; ?>bulk" class="nav-item" data-view="bulk">
                    <svg class="nav-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Bulk Search
                </a>
                <a href="<?php echo $base; ?>dns" class="nav-item" data-view="dns">
                    <svg class="nav-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    DNS Tools
                </a>
            </nav>
        </aside>

        <header class="header">
            <div class="page-title" id="page-title">Dashboard</div>
        </header>

        <main class="viewport" id="app-viewport">

            <!-- SINGLE LOOKUP VIEW -->
            <div id="view-dashboard" class="content-view">
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom:20px;">Domain Loopkup</h3>
                    <div class="input-wrapper">
                        <input type="text" id="lookup-input" class="form-control"
                            placeholder="Enter a domain (e.g. google.com)">
                        <button id="lookup-btn" class="btn-primary">Search</button>
                    </div>
                </div>

                <div id="lookup-result" class="hidden"></div>
            </div>

            <!-- BULK PROCESS VIEW -->
            <div id="view-bulk" class="content-view hidden">
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom:10px;">Bulk Processor</h3>
                    <p style="color:var(--text-muted); margin-bottom:24px;">Upload a list of domains (.txt or .csv) for
                        parallel analysis.</p>

                    <div id="bulk-upload-form"
                        style="border: 2px dashed var(--border-strong); padding: 32px; text-align: center; border-radius: 8px;">
                        <input type="file" id="bulk-file" class="hidden" accept=".txt,.csv">
                        <label for="bulk-file" class="btn-primary"
                            style="display:inline-block; margin-bottom:12px;">Select File</label>
                        <div style="font-size:12px; color:var(--text-muted);">
                            Max 5,000 domains • <a href="<?php echo $base; ?>assets/sample_domains.csv" download
                                style="color:var(--primary); text-decoration:none; font-weight:500;">Download Sample
                                CSV</a>
                        </div>
                    </div>

                    <div id="bulk-progress-container" class="hidden" style="margin-top:32px;">
                        <div style="display:flex; justify-content:space-between; font-weight:600; margin-bottom:8px;">
                            <span id="bulk-status">Preparing...</span>
                            <span id="bulk-percent">0%</span>
                        </div>
                        <div
                            style="height:6px; background:var(--bg-element); border-radius:3px; overflow:hidden; margin-bottom:16px;">
                            <div id="bulk-bar"
                                style="height:100%; width:0%; background:var(--primary); transition:width 0.2s;"></div>
                        </div>

                        <!-- Actions Row - shown immediately when bulk starts -->
                        <div id="bulk-actions" style="margin-bottom:16px; text-align:right;">
                            <button id="bulk-export-btn" class="btn-primary" disabled>
                                DOWNLOAD REPORT
                            </button>
                        </div>

                        <div class="table-scroller">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Domain</th>
                                        <th>Registrar</th>
                                        <th>Owner</th>
                                        <th>Created</th>
                                        <th>Expiry</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bulk-result-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DNS DIG VIEW -->
            <div id="view-dns" class="content-view hidden">
                <div class="card">
                    <h3 style="margin-top:0; margin-bottom:20px;">DNS Resolver</h3>
                    <div class="input-wrapper">
                        <input type="text" id="dns-domain" class="form-control" placeholder="Host e.g. example.com">
                        <select id="dns-type" class="form-control" style="flex:0 0 120px;">
                            <option value="A">A</option>
                            <option value="AAAA">AAAA</option>
                            <option value="CNAME">CNAME</option>
                            <option value="MX">MX</option>
                            <option value="TXT">TXT</option>
                            <option value="NS">NS</option>
                            <option value="SOA">SOA</option>
                            <option value="PTR">PTR</option>
                            <option value="SRV">SRV</option>
                            <option value="CAA">CAA</option>
                            <option value="ANY">ANY</option>
                        </select>
                        <button id="dns-btn" class="btn-primary">QUERY</button>
                    </div>
                </div>

                <div id="dns-result" class="card hidden"></div>
            </div>

        </main>
    </div>

    <footer class="app-footer">
        Built by <a href="https://github.com/anonymousraft" target="_blank">@anonymousraft</a> · GPL License ·
        <?php echo date('Y'); ?>
    </footer>

    <script>
        window.BASE_PATH = '<?php echo $base; ?>';
        window.APP_CONFIG = {
            BULK_LIMIT: <?php echo \App\Config::get('BULK_DOMAIN_LIMIT', 5000); ?>,
            BULK_DELAY: <?php echo \App\Config::get('BULK_RATE_DELAY', 250); ?>
        };
    </script>
    <script src="<?php echo $base; ?>assets/js/app.js"></script>
</body>

</html>