<footer style="
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #333;
    color: white;
    padding: 15px 0;
    text-align: center;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    z-index: 100;
    font-family: 'Arial', sans-serif;
">
    <p style="
        margin: 0;
        font-size: 14px;
        letter-spacing: 0.5px;
    ">
        &copy; <?php echo date("Y"); ?> RunBerry Grocery Store. All rights reserved.
    </p>
    
    <style>
    /* 移除 fixed 定位，改用 flex 布局实现 sticky footer */
        footer {
            width: 100%;
            background: #333;
            color: white;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            font-family: 'Arial', sans-serif;
            margin-top: auto; /* 关键属性：推动 footer 到底部 */
        }

        /* 使用 flex 布局确保 footer 在底部 */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            color: #333;
            line-height: 1.6;
        }

        /* 主要内容区域自动扩展 */
        .main-content {
            flex: 1;
        }

        /* 移动端适配 */
        @media (max-width: 768px) {
            footer {
                padding: 12px 0;
            }
        }
    </style>
</footer>