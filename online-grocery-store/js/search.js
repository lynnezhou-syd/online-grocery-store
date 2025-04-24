// search.js
document.addEventListener('DOMContentLoaded', function () {
    const searchBox = document.getElementById('searchBox');
    const searchResults = document.getElementById('search-results');

    if (searchBox) {
        searchBox.value = "";

        if (searchResults) {
            searchResults.style.display = "none";
        }

        searchBox.addEventListener('keyup', function () {
            const query = this.value;
            if (query) {
                searchProducts(query); 
            } else {
                
                if (searchResults) {
                    searchResults.style.display = "none";
                }
            }
        });

        // 监听 Enter 键
        searchBox.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.keyCode === 13) {
                event.preventDefault(); // 阻止默认行为
                performSearch(); // 调用搜索函数
            }
        });
    }
});


// Real-time search function
function searchProducts(query) {
    if (!query) {
        // 如果搜索框为空，隐藏搜索结果
        const searchResults = document.getElementById("search-results");
        if (searchResults) {
            searchResults.style.display = "none";
        }
        return;
    }

    // 发送请求到 search.php
    fetch(`search.php?query=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const resultsContainer = document.getElementById("search-results");
            if (resultsContainer) {
                resultsContainer.innerHTML = "";
                resultsContainer.style.display = "block"; // 显示搜索结果

                if (data.length === 0) {
                    resultsContainer.innerHTML = "<p>No products found 😢</p>";
                    return;
                }

                // 显示搜索结果
                data.forEach(product => {
                    const productElement = document.createElement("a"); // 使用 <a> 标签
                    productElement.classList.add("search-item");
                    productElement.href = `product_details.php?id=${product.id}`; // 跳转到商品详情页
                    productElement.innerHTML = `
                        <div class="search-info">
                            <p><strong>${product.name}</strong></p>
                        </div>
                    `;
                    resultsContainer.appendChild(productElement);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
            const resultsContainer = document.getElementById("search-results");
            if (resultsContainer) {
                resultsContainer.innerHTML = '<p>Error loading search results.</p>';
            }
        });
}

// Search function (for both button click and Enter key)
function performSearch() {
    const query = document.getElementById('searchBox').value;
    if (query) {
        window.location.href = `search.php?query=${encodeURIComponent(query)}`;
    } else {
        alert('Please enter a search term.');
    }
}


function redirectToSearchResults() {
    console.log('redirectToSearchResults called'); // 调试日志
    const query = document.getElementById('searchBox').value;
    if (query.trim() !== '') {
        window.location.href = `search.php?query=${encodeURIComponent(query)}`;
    } else {
        alert('Please enter a search term.');
    }
    return false; // 阻止表单默认提交行为
}

// 清空搜索框
function clearSearch() {
    const searchBox = document.getElementById('searchBox');
    if (searchBox) {
        searchBox.value = ''; // 清空输入框
        searchBox.focus(); // 聚焦到输入框
    }

    // 隐藏搜索结果
    const searchResults = document.getElementById('search-results');
    if (searchResults) {
        searchResults.style.display = 'none';
    }
}