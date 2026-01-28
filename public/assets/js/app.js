document.addEventListener('DOMContentLoaded', () => {
    const lookupBtn = document.getElementById('lookup-btn');
    const lookupInput = document.getElementById('lookup-input');
    const lookupResult = document.getElementById('lookup-result');

    // View Switching
    function switchView(view) {
        document.querySelectorAll('.content-view').forEach(v => v.classList.add('hidden'));
        document.querySelectorAll('.nav-item').forEach(l => l.classList.remove('active'));

        const activeView = document.getElementById(`view-${view}`);
        const activeLink = document.querySelector(`[data-view="${view}"]`);

        if (activeView) {
            activeView.classList.remove('hidden');
            activeLink?.classList.add('active');

            const titles = {
                dashboard: 'Domain Research',
                bulk: 'Bulk Analysis',
                dns: 'DNS Tools'
            };
            document.getElementById('page-title').textContent = titles[view] || 'Dashboard';
        }
    }

    document.querySelectorAll('.nav-item').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            switchView(link.getAttribute('data-view'));
        });
    });

    function showToast(message, type = 'info') {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `carbon-toast ${type}`;

        let icon = '';
        if (type === 'success') icon = '<svg width="20" height="20" fill="none" class="text-success"><path d="M10 20C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10zm-1-5l5.5-5.5-1.41-1.42L10 12.17 6.91 9.09 5.5 10.5 9 14z" fill="currentColor"/></svg>';
        else if (type === 'error') icon = '<svg width="20" height="20" fill="none" class="text-danger"><path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm1 15H9v-2h2v2zm0-4H9V5h2v6z" fill="currentColor"/></svg>';
        else icon = '<svg width="20" height="20" fill="none" class="text-spark"><path d="M11 7h2v2h-2zm0 4h2v6h-2zM10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" fill="currentColor"/></svg>';

        toast.innerHTML = `${icon}<span>${message}</span>`;
        container.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => toast.classList.add('active'));

        // Remove after 3s
        setTimeout(() => {
            toast.classList.remove('active');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Lookup Logic
    if (lookupBtn) {
        lookupBtn.addEventListener('click', performLookup);
        lookupInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') performLookup();
        });
    }

    async function performLookup() {
        const domain = lookupInput.value.trim();
        if (!domain) {
            showToast('Please enter a domain.', 'error');
            return;
        }

        lookupBtn.disabled = true;
        lookupBtn.textContent = 'Searching...';
        lookupResult.classList.add('hidden');

        try {
            const data = await apiRequest('/api/lookup', `domain=${encodeURIComponent(domain)}`);
            if (data.success) {
                showLookupResult(data.data);
                showToast('Target entity analyzed.', 'success');
            } else {
                showToast(data.error || 'Lookup failed.', 'error');
            }
        } catch (e) {
            showToast('System connection failure.', 'error');
        } finally {
            lookupBtn.disabled = false;
            lookupBtn.textContent = 'Search';
        }
    }

    function showLookupResult(data) {
        // Construct a clean table result
        lookupResult.innerHTML = `
            <div class="card">
                <table class="data-table">
                    <tbody>
                        ${Object.entries(data).map(([key, value]) => `
                            <tr>
                                <td style="width:30%; font-weight:500;">${key.replace(/_/g, ' ')}</td>
                                <td class="font-mono text-main">${value || '—'}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        lookupResult.classList.remove('hidden');
    }

    // DNS Logic
    const dnsBtn = document.getElementById('dns-btn');
    if (dnsBtn) {
        dnsBtn.addEventListener('click', async () => {
            const domain = document.getElementById('dns-domain').value.trim();
            const type = document.getElementById('dns-type').value;
            if (!domain) return;

            const res = document.getElementById('dns-result');
            dnsBtn.disabled = true;
            res.innerHTML = '<div style="text-align:center; padding:20px; color:var(--text-muted);">Querying DNS...</div>';
            res.classList.remove('hidden');

            const resp = await apiRequest('/api/dns/query', `domain=${encodeURIComponent(domain)}&type=${type}`);

            if (resp.success && resp.data.Answer) {
                res.innerHTML = `
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Record</th>
                                <th>TTL</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${resp.data.Answer.map(ans => `
                                <tr>
                                    <td class="font-mono" style="color:var(--text-main);">${ans.data}</td>
                                    <td><span class="badge badge-soft">${ans.TTL}s</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
            } else {
                res.innerHTML = '<div style="text-align:center; padding:20px; color:var(--text-muted);">No records found.</div>';
            }
            dnsBtn.disabled = false;
        });
    }

    // Mail Armor Logic
    const msBtn = document.getElementById('ms-btn');
    if (msBtn) {
        msBtn.addEventListener('click', async () => {
            const domain = document.getElementById('ms-domain').value.trim();
            if (!domain) return showToast('Please enter a domain', 'error');

            const res = document.getElementById('ms-result');
            res.innerHTML = '<div style="padding:20px; text-align:center;">Analyzing Security Policy...</div>';
            res.classList.remove('hidden');
            msBtn.disabled = true;

            const resp = await apiRequest('/api/email-security', `domain=${encodeURIComponent(domain)}`);

            if (resp.success) {
                const d = resp.data;
                const badgeClass = d.assessment.status === 'Secure' ? 'success' : (d.assessment.status === 'Vulnerable' ? 'danger' : 'warning');

                res.innerHTML = `
                    <div style="padding:20px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; border-bottom:1px solid var(--border-subtle); padding-bottom:15px;">
                            <h4 style="margin:0; font-size:16px;">Security Assessment</h4>
                            <span class="badge badge-${badgeClass}" style="font-size:14px;">${d.assessment.status}</span>
                        </div>
                        
                        <p style="margin-bottom:20px; color:var(--text-main); font-size:15px;">
                            ${d.assessment.summary}
                        </p>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                            <div style="background:var(--bg-app); padding:15px; border-radius:6px; border:1px solid var(--border-subtle);">
                                <div style="font-weight:600; margin-bottom:8px; color:var(--text-muted); font-size:12px;">SPF RECORD</div>
                                <div style="font-family:monospace; word-break:break-all; font-size:13px; margin-bottom:8px;">${d.spf.raw || '<span class="text-danger">Missing</span>'}</div>
                                <div style="font-size:13px;">Policy: <strong>${d.spf.all_mechanism || 'N/A'}</strong></div>
                            </div>

                            <div style="background:var(--bg-app); padding:15px; border-radius:6px; border:1px solid var(--border-subtle);">
                                <div style="font-weight:600; margin-bottom:8px; color:var(--text-muted); font-size:12px;">DMARC RECORD</div>
                                <div style="font-family:monospace; word-break:break-all; font-size:13px; margin-bottom:8px;">${d.dmarc.raw || '<span class="text-danger">Missing</span>'}</div>
                                <div style="font-size:13px;">Policy: <strong>${d.dmarc.policy || 'N/A'}</strong></div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                res.innerHTML = `<div style="padding:20px; color:var(--danger);">Error: ${resp.error}</div>`;
            }
            msBtn.disabled = false;
        });
    }

    // Bulk Logic / Variables
    let bulkRows = []; // Store results for export

    const bulkFile = document.getElementById('bulk-file');
    const exportBtn = document.getElementById('bulk-export-btn');

    if (bulkFile) {
        bulkFile.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = async (event) => {
                const text = event.target.result;
                const domains = text.split(/[\s,]+/).filter(d => d.includes('.'));

                // Limit Check
                const limit = window.APP_CONFIG?.BULK_LIMIT || 5000;
                if (domains.length > limit) {
                    showToast(`File too large. Maximum ${limit} domains allowed.`, 'error');
                    document.getElementById('bulk-progress-container').classList.add('hidden');
                    document.getElementById('bulk-upload-form').classList.remove('hidden');
                    return;
                }

                document.getElementById('bulk-progress-container').classList.remove('hidden');
                document.getElementById('bulk-upload-form').classList.add('hidden'); // Hide upload form
                document.getElementById('bulk-result-body').innerHTML = '';
                if (exportBtn) exportBtn.disabled = true; // Disable until done
                bulkRows = []; // Reset data

                let count = 0;
                const delayMs = window.APP_CONFIG?.BULK_DELAY || 250;

                for (const d of domains) {
                    count++;
                    updateBulkProgress(Math.round((count / domains.length) * 100), `Processing ${d}...`);

                    const resp = await apiRequest('/api/lookup', `domain=${encodeURIComponent(d)}`);
                    await new Promise(r => setTimeout(r, delayMs)); // Rate limit delay

                    // Store ALL fields for export
                    if (resp.success) {
                        const r = resp.data;
                        bulkRows.push([
                            d,
                            r['Registrar'] || '',
                            r['Owner'] || '',
                            r['Creation Date'] || '',
                            r['Expiry Date'] || '',
                            r['Name Servers'] || '',
                            r['Status'] || '',
                            'Active'
                        ]);
                    } else {
                        bulkRows.push([d, '', '', '', '', '', '', 'Failed: ' + resp.error]);
                    }

                    appendBulkRow(d, resp);
                }
                updateBulkProgress(100, 'Batch Analysis Complete');
                if (exportBtn) exportBtn.disabled = false; // Enable download
                showToast('Batch processing complete.', 'success');
            };
            reader.readAsText(file);
        });
    }

    // Export Logic
    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Domain,Registrar,Owner,Creation Date,Expiry Date,Name Servers,Status,Result\n"; // Full Header

            bulkRows.forEach(row => {
                // Escape commas in values
                const escapedRow = row.map(val => `"${(val || '').replace(/"/g, '""')}"`);
                csvContent += escapedRow.join(",") + "\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "carbon_intelligence_report.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            showToast('Report downloaded.', 'success');
        });
    }

    async function apiRequest(url, body) {
        const cleanUrl = url.startsWith('/') ? url.substring(1) : url;
        const finalUrl = (window.BASE_PATH || '/') + cleanUrl;

        const response = await fetch(finalUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        });
        return await response.json();
    }

    function updateBulkProgress(percent, status) {
        document.getElementById('bulk-bar').style.width = percent + '%';
        document.getElementById('bulk-bar').className = 'progress-bar-spark'; /* Glow */
        document.getElementById('bulk-percent').textContent = percent + '%';
        document.getElementById('bulk-status').textContent = status;
    }

    function appendBulkRow(domain, resp) {
        const body = document.getElementById('bulk-result-body');
        const row = document.createElement('tr');

        if (resp.success) {
            const d = resp.data;
            row.innerHTML = `
                <td style="font-weight:500;">${domain}</td>
                <td>${d['Registrar'] || '—'}</td>
                <td>${d['Owner'] || '—'}</td>
                <td>${d['Creation Date'] || '—'}</td>
                <td>${d['Expiry Date'] || '—'}</td>
                <td><span class="text-success" style="font-weight:600;">Active</span></td>
            `;
        } else {
            row.innerHTML = `
                <td style="color:var(--text-muted); text-decoration:line-through;">${domain}</td>
                <td colspan="4" class="text-danger">${resp.error}</td>
                <td><span class="text-danger" style="font-weight:600;">Failed</span></td>
            `;
        }
        body.insertBefore(row, body.firstChild);
    }
});
