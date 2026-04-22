<?php include 'f_header.php'; ?>

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --card-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }

    .planner-container {
        padding: 60px 5%;
        max-width: 1300px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 40px;
        min-height: 80vh;
    }

    @media (max-width: 1000px) {
        .planner-container {
            grid-template-columns: 1fr;
        }
    }

    /* Left Side: Controls */
    .planner-sidebar {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 35px;
        box-shadow: var(--card-shadow);
        height: fit-content;
        position: sticky;
        top: 100px;
    }

    .sidebar-section {
        margin-bottom: 25px;
    }

    .sidebar-section label {
        display: block;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 10px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-control {
        width: 100%;
        padding: 14px 20px;
        border-radius: 16px;
        border: 2px solid #e2e8f0;
        background: white;
        font-family: inherit;
        font-size: 15px;
        transition: all 0.3s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(1, 105, 111, 0.1);
    }

    /* Custom Expense Rows */
    .custom-expense-row {
        display: grid;
        grid-template-columns: 2fr 1fr 40px;
        gap: 10px;
        margin-bottom: 10px;
        align-items: center;
    }

    .btn-remove {
        background: #fee2e2;
        color: #ef4444;
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-remove:hover {
        background: #ef4444;
        color: white;
    }

    .btn-add {
        background: #f1f5f9;
        color: #475569;
        border: 2px dashed #cbd5e1;
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: 0.2s;
        margin-top: 5px;
    }

    .btn-add:hover {
        background: #e2e8f0;
        border-color: #94a3b8;
    }

    /* Right Side: Results */
    .planner-display {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .total-card {
        background: linear-gradient(135deg, var(--primary) 0%, #014f54 100%);
        border-radius: 35px;
        padding: 45px;
        color: white;
        text-align: center;
        box-shadow: 0 25px 50px rgba(1, 105, 111, 0.2);
        position: relative;
        overflow: hidden;
    }

    .total-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .total-card h3 {
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0 0 15px 0;
        opacity: 0.9;
    }

    .total-card .amount {
        font-size: 64px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -2px;
    }

    .total-card .tax-note {
        font-size: 14px;
        opacity: 0.7;
        margin-top: 15px;
    }

    .breakdown-card {
        background: white;
        border-radius: 30px;
        padding: 35px;
        box-shadow: var(--card-shadow);
    }

    .breakdown-item {
        display: flex;
        justify-content: space-between;
        padding: 18px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 15px;
    }

    .breakdown-item:last-child {
        border-bottom: none;
    }

    .breakdown-item .label {
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .breakdown-item .label i {
        width: 20px;
        color: var(--primary);
    }

    .breakdown-item .value {
        color: #1e293b;
        font-weight: 800;
    }

    .progress-container {
        margin-top: 25px;
        height: 10px;
        background: #f1f5f9;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
    }

    .progress-bar {
        height: 100%;
        transition: width 0.4s ease;
    }

    .btn-export {
        background: #1e293b;
        color: white;
        padding: 16px 30px;
        border-radius: 18px;
        text-decoration: none;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: 0.3s;
        margin-top: 10px;
    }

    .btn-export:hover {
        background: #000;
        transform: translateY(-2px);
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .planner-sidebar, .total-card, .breakdown-card {
        animation: fadeInUp 0.6s ease-out forwards;
    }
</style>

<div class="planner-container">
    <!-- Configuration Sidebar -->
    <div class="planner-sidebar">
        <h2 style="font-weight: 800; margin-bottom: 30px; letter-spacing: -1px;">Plan Your Trip</h2>
        
        <div class="sidebar-section">
            <label>Trip Package</label>
            <select id="pkg_select" class="form-control">
                <option value="0" data-price="0">Select a package</option>
                <?php
                $pkgs = $conn->query("SELECT title, price FROM packages");
                while($p = $pkgs->fetch_assoc()){
                    echo "<option value='".$p['price']."' data-title='".$p['title']."'>".$p['title']." (₹".number_format($p['price']).")</option>";
                }
                ?>
            </select>
        </div>

        <div class="sidebar-section">
            <label>Travelers</label>
            <div style="position: relative;">
                <input type="number" id="travelers_count" class="form-control" value="1" min="1">
                <i class="fas fa-users" style="position: absolute; right: 20px; top: 18px; color: #94a3b8;"></i>
            </div>
        </div>

        <div class="sidebar-section">
            <label>Extra Expenses</label>
            <div id="custom_expenses_list">
                <!-- Custom expenses added here -->
            </div>
            <button class="btn-add" id="add_expense_btn">
                <i class="fas fa-plus"></i> Add Custom Expense
            </button>
        </div>

        <div class="sidebar-section" style="margin-top: 40px; padding-top: 25px; border-top: 1px solid #e2e8f0;">
            <label>Service Tax Rate</label>
            <select id="tax_rate" class="form-control">
                <option value="0.05">Standard (5%)</option>
                <option value="0.12">Premium (12%)</option>
                <option value="0.18">Luxury (18%)</option>
            </select>
        </div>
    </div>

    <!-- Results Display -->
    <div class="planner-display">
        <div class="total-card">
            <h3>Estimated Total Budget</h3>
            <p class="amount" id="grand_total">₹0</p>
            <p class="tax-note" id="tax_amount_display">Incl. ₹0 taxes</p>
        </div>

        <div class="breakdown-card">
            <h3 style="font-weight: 800; margin-bottom: 25px;">Budget Breakdown</h3>
            
            <div class="breakdown-item">
                <div class="label"><i class="fas fa-suitcase-rolling"></i> Base Package</div>
                <div class="value" id="breakdown_package">₹0</div>
            </div>
            
            <div class="breakdown-item">
                <div class="label"><i class="fas fa-plus-circle"></i> Additional Costs</div>
                <div class="value" id="breakdown_extras">₹0</div>
            </div>

            <div class="breakdown-item">
                <div class="label"><i class="fas fa-percentage"></i> Service Tax</div>
                <div class="value" id="breakdown_tax">₹0</div>
            </div>

            <div class="progress-container">
                <div id="prog_base" class="progress-bar" style="background: var(--primary); width: 0%;"></div>
                <div id="prog_extra" class="progress-bar" style="background: var(--accent); width: 0%;"></div>
                <div id="prog_tax" class="progress-bar" style="background: #94a3b8; width: 0%;"></div>
            </div>

            <div style="display: flex; gap: 20px; margin-top: 15px; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #64748b;">
                <span><i class="fas fa-circle" style="color: var(--primary);"></i> Base</span>
                <span><i class="fas fa-circle" style="color: var(--accent);"></i> Extras</span>
                <span><i class="fas fa-circle" style="color: #94a3b8;"></i> Tax</span>
            </div>

            <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <a href="javascript:window.print()" class="btn-export">
                    <i class="fas fa-print"></i> Print PDF
                </a>
                <a href="support.php" class="btn-export" style="background: #f1f5f9; color: #1e293b; border: 1px solid #e2e8f0;">
                    <i class="fas fa-headset"></i> Get Help
                </a>
            </div>
        </div>

        <div style="background: #fff9f0; border-radius: 20px; padding: 25px; border: 1px solid #ffe8cc; color: #856404; display: flex; gap: 15px;">
            <i class="fas fa-lightbulb" style="font-size: 24px; margin-top: 5px;"></i>
            <p style="margin: 0; font-size: 14px; line-height: 1.6;">
                <strong>Pro Tip:</strong> Most travelers spend an additional 20% on miscellaneous items like local souvenirs and snacks. We recommend adding a "Buffer" expense!
            </p>
        </div>
    </div>
</div>

<script>
    const pkgSelect = document.getElementById('pkg_select');
    const travelersInput = document.getElementById('travelers_count');
    const taxRateSelect = document.getElementById('tax_rate');
    const expensesList = document.getElementById('custom_expenses_list');
    const addExpenseBtn = document.getElementById('add_expense_btn');

    const grandTotalDisplay = document.getElementById('grand_total');
    const taxDisplay = document.getElementById('tax_amount_display');
    const breakdownPkg = document.getElementById('breakdown_package');
    const breakdownExtras = document.getElementById('breakdown_extras');
    const breakdownTax = document.getElementById('breakdown_tax');

    const progBase = document.getElementById('prog_base');
    const progExtra = document.getElementById('prog_extra');
    const progTax = document.getElementById('prog_tax');

    function formatCurrency(num) {
        return "₹" + Math.round(num).toLocaleString('en-IN');
    }

    function calculate() {
        let pkgBase = parseFloat(pkgSelect.value) || 0;
        let travelers = parseInt(travelersInput.value) || 1;
        let baseTotal = pkgBase * travelers;

        let extraTotal = 0;
        document.querySelectorAll('.extra-amount').forEach(input => {
            extraTotal += parseFloat(input.value) || 0;
        });

        let taxRate = parseFloat(taxRateSelect.value);
        let taxableAmount = baseTotal + extraTotal;
        let taxAmount = taxableAmount * taxRate;
        let total = taxableAmount + taxAmount;

        // Update UI
        grandTotalDisplay.innerText = formatCurrency(total);
        taxDisplay.innerText = "Incl. " + formatCurrency(taxAmount) + " taxes";
        breakdownPkg.innerText = formatCurrency(baseTotal);
        breakdownExtras.innerText = formatCurrency(extraTotal);
        breakdownTax.innerText = formatCurrency(taxAmount);

        // Update Progress Bars
        if (total > 0) {
            progBase.style.width = (baseTotal / total * 100) + "%";
            progExtra.style.width = (extraTotal / total * 100) + "%";
            progTax.style.width = (taxAmount / total * 100) + "%";
        } else {
            progBase.style.width = "0%";
            progExtra.style.width = "0%";
            progTax.style.width = "0%";
        }
    }

    function addExpenseRow(name = '', amount = '') {
        const div = document.createElement('div');
        div.className = 'custom-expense-row';
        div.innerHTML = `
            <input type="text" class="form-control" placeholder="e.g. Flight" value="${name}">
            <input type="number" class="form-control extra-amount" placeholder="0" value="${amount}">
            <button class="btn-remove"><i class="fas fa-times"></i></button>
        `;
        
        div.querySelector('.btn-remove').onclick = function() {
            div.remove();
            calculate();
        };

        div.querySelectorAll('input').forEach(input => {
            input.oninput = calculate;
        });

        expensesList.appendChild(div);
    }

    pkgSelect.onchange = calculate;
    travelersInput.oninput = calculate;
    taxRateSelect.onchange = calculate;
    addExpenseBtn.onclick = () => addExpenseRow();

    // Initial State
    calculate();
</script>

<?php include 'f_footer.php'; ?>