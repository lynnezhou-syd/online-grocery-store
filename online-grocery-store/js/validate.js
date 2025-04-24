document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');
    const mobileInput = document.getElementById('mobile_number');

    // 邮箱验证函数
    function validateEmail() {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const hasValue = emailInput.value.trim() !== '';
        const isValid = hasValue ? emailRegex.test(emailInput.value) : true;
        
        // 显示/隐藏错误消息
        const errorMsg = emailInput.nextElementSibling;
        if (errorMsg && errorMsg.classList.contains('error-message')) {
            errorMsg.textContent = isValid ? '' : 'Please enter a valid email address (e.g., user@example.com)';
            errorMsg.style.display = isValid ? 'none' : 'block';
        }
        
        return isValid || !hasValue; 
    }

    // 手机号验证函数
    function validateMobile() {
        const mobileRegex = /^[0-9]{10}$/;
        const hasValue = mobileInput.value.trim() !== '';
        const isValid = hasValue ? mobileRegex.test(mobileInput.value) : true;
        
        const errorMsg = mobileInput.nextElementSibling;
        if (errorMsg && errorMsg.classList.contains('error-message')) {
            errorMsg.textContent = isValid ? '' : 'Please enter a 10-digit phone number (e.g., 0412345678)';
            errorMsg.style.display = isValid ? 'none' : 'block';
        }
        
        return isValid || !hasValue;
    }

    // 整体表单验证
    function validateForm() {
        // 检查所有必填字段是否已填写
        const allFieldsFilled = Array.from(form.querySelectorAll('[required]'))
            .every(input => input.value.trim() !== '');
        
        // 检查邮箱格式
        const emailValid = validateEmail();
        const mobileValid = validateMobile();

        // 禁用/启用提交按钮
        submitBtn.disabled = !(allFieldsFilled && emailValid && mobileValid);
    }

    // 实时验证
    emailInput.addEventListener('input', validateForm);
    mobileInput.addEventListener('input', validateForm);
    form.addEventListener('input', validateForm);

    // 修改表单提交处理
    form.addEventListener('submit', async function(event) {
        event.preventDefault(); // 阻止默认表单提交
        
        try {
            const response = await fetch('../backend/process_order.php', {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // 标识为AJAX请求
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // 订单成功，跳转到确认页面
                window.location.href = '/confirmation.php';
            } else {
                // 显示库存不足错误并跳转
                alert(result.message);
                window.location.href = 'cart_detail.php';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while processing your order.');
        }
    });
});