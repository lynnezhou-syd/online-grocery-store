// ✅ 切换侧边栏
function toggleSidebar() {
    let sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("show");

    // 🚀 如果侧边栏展开，监听点击事件，点击外部区域时关闭
    if (sidebar.classList.contains("show")) {
        document.addEventListener("click", closeSidebarOutside);
    } else {
        document.removeEventListener("click", closeSidebarOutside);
    }
}

// ✅ 点击页面其他区域关闭侧边栏
function closeSidebarOutside(event) {
    let sidebar = document.getElementById("sidebar");
    let toggleButton = document.querySelector(".sidebar-toggle");

    // 🚀 检查点击的是否是侧边栏或侧边栏按钮
    if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
        sidebar.classList.remove("show");
        document.removeEventListener("click", closeSidebarOutside);
    }
}

// ✅ 动态加载分类，而不是刷新页面
function loadCategory(page) {
    fetch(page)
        .then(response => response.text())
        .then(data => {
            document.getElementById('content').innerHTML = data;
            history.pushState(null, "", page); // 🚀 修改URL但不刷新页面
        })
        .catch(error => console.error("Error loading category:", error));
}

// ✅ 监听浏览器返回按钮，保持当前分类
window.onpopstate = function () {
    let path = window.location.pathname.split('/').pop();
    if (path) {
        loadCategory(path);
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // 获取所有分类项
    const categoryItems = document.querySelectorAll('.category-item');
    
    // 为每个分类项添加点击事件
    categoryItems.forEach(item => {
        const categoryLink = item.querySelector('.category-link');
        const subCategories = item.querySelector('.sub-categories');
        const dropdownIcon = item.querySelector('.dropdown-icon');
        
        // 点击分类链接时跳转到商品页面
        categoryLink.addEventListener('click', function(e) {
            // 判断点击的目标是否是下拉图标，如果是，阻止跳转
            if (e.target !== dropdownIcon) {
                window.location.href = categoryLink.href; // 跳转到分类页面
            }
        });
        
        // 点击下拉图标时切换子分类的显示状态
        dropdownIcon.addEventListener('click', function(e) {
            e.preventDefault(); // 阻止默认的链接行为（防止跳转）
            e.stopPropagation(); // 阻止事件传播，防止触发 categoryLink 的点击事件
            item.classList.toggle('active');
            subCategories.classList.toggle('active');
        });
    });
});



