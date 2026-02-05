// Real-time Updates for Bursary Dashboard
// This file handles AJAX requests for real-time transaction updates

class BursaryRealTime {
    constructor() {
        this.autoRefresh = false;
        this.refreshInterval = null;
        this.currentTerm = null;
        this.currentSession = null;
        
        this.init();
    }

    init() {
        // Get current term and session from data attributes or hidden inputs
        this.currentTerm = document.querySelector('input[name="current_term"]')?.value || 
                          document.body.getAttribute('data-current-term');
        this.currentSession = document.querySelector('input[name="current_session"]')?.value || 
                              document.body.getAttribute('data-current-session');

        // Initialize event listeners
        this.setupEventListeners();
        
        // Start auto-refresh by default
        this.toggleAutoRefresh(true);
    }

    setupEventListeners() {
        // Refresh button
        const refreshBtn = document.getElementById('refresh-transactions');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.refreshTransactions());
        }

        // Auto-refresh toggle
        const toggleBtn = document.getElementById('toggle-auto-refresh');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                this.toggleAutoRefresh(!this.autoRefresh);
            });
        }

        // Search form
        const searchForm = document.getElementById('search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.performSearch();
            });
        }

        // Clear filters
        const clearBtn = document.getElementById('clear-filters');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearFilters());
        }

        // Export data
        const exportBtn = document.getElementById('export-data');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportData());
        }
    }

    async refreshTransactions() {
        try {
            const response = await fetch('api/transactions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get_recent_transactions',
                    term: this.currentTerm,
                    session: this.currentSession,
                    limit: 10
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.success) {
                this.updateTransactionFeed(data.transactions);
                this.updateLastUpdateTime();
                this.animateRefresh();
            } else {
                console.error('Error fetching transactions:', data.message);
            }
        } catch (error) {
            console.error('Error refreshing transactions:', error);
            // Fallback: show error message to user
            this.showError('Failed to refresh transactions. Please try again.');
        }
    }

    updateTransactionFeed(transactions) {
        const feed = document.getElementById('transaction-feed');
        if (!feed || !transactions) return;

        // Clear existing content
        feed.innerHTML = '';

        // Add new transactions
        transactions.forEach(transaction => {
            const row = document.createElement('tr');
            row.className = 'transaction-item';
            row.innerHTML = `
                <td>${this.formatTime(transaction.payment_date)}</td>
                <td>${this.escapeHtml(transaction.name)}</td>
                <td>${transaction.amount_display}</td>
                <td><span class="badge bg-primary">${this.escapeHtml(transaction.payment_method)}</span></td>
                <td>${this.escapeHtml(transaction.receipt_number)}</td>
            `;
            feed.appendChild(row);
        });
    }

    updateLastUpdateTime() {
        const element = document.getElementById('last-update');
        if (element) {
            element.textContent = new Date().toLocaleTimeString();
        }
    }

    animateRefresh() {
        const feed = document.getElementById('transaction-feed');
        if (feed) {
            feed.style.opacity = '0.5';
            setTimeout(() => {
                feed.style.opacity = '1';
            }, 300);
        }
    }

    toggleAutoRefresh(enable) {
        this.autoRefresh = enable;
        const btn = document.getElementById('toggle-auto-refresh');
        const icon = btn?.querySelector('i');
        
        if (btn && icon) {
            if (enable) {
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');
                icon.classList.remove('fa-play');
                icon.classList.add('fa-pause');
                this.startAutoRefresh();
            } else {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-play');
                this.stopAutoRefresh();
            }
        }
    }

    startAutoRefresh() {
        this.stopAutoRefresh(); // Clear any existing interval
        this.refreshInterval = setInterval(() => {
            this.refreshTransactions();
        }, 30000); // Update every 30 seconds
    }

    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }

    async performSearch() {
        const formData = new FormData(document.getElementById('search-form'));
        const searchParams = {
            action: 'search_transactions',
            student: formData.get('search-student'),
            method: formData.get('filter-method'),
            date_from: formData.get('filter-date-from'),
            date_to: formData.get('filter-date-to'),
            term: this.currentTerm,
            session: this.currentSession
        };

        try {
            const response = await fetch('api/transactions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(searchParams)
            });

            const data = await response.json();
            
            if (data.success) {
                this.updateTransactionFeed(data.transactions);
                this.showSuccess('Search completed successfully');
            } else {
                this.showError('Search failed: ' + data.message);
            }
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Search failed. Please try again.');
        }
    }

    clearFilters() {
        const form = document.getElementById('search-form');
        if (form) {
            form.reset();
        }
        // Refresh with default parameters
        this.refreshTransactions();
    }

    async exportData() {
        const formData = new FormData(document.getElementById('search-form'));
        const exportParams = {
            action: 'export_transactions',
            format: 'csv', // Could be 'pdf' as well
            student: formData.get('search-student'),
            method: formData.get('filter-method'),
            date_from: formData.get('filter-date-from'),
            date_to: formData.get('filter-date-to'),
            term: this.currentTerm,
            session: this.currentSession
        };

        try {
            const response = await fetch('api/transactions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(exportParams)
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `transactions_${new Date().toISOString().slice(0, 10)}.csv`;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
                this.showSuccess('Export completed successfully');
            } else {
                this.showError('Export failed. Please try again.');
            }
        } catch (error) {
            console.error('Export error:', error);
            this.showError('Export failed. Please try again.');
        }
    }

    showError(message) {
        this.showNotification(message, 'danger');
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        `;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }

    formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new BursaryRealTime();
});