<style>
    .container-in {
        text-align: center;
        padding: 50px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .icon-wrapper {
        margin-bottom: 30px;
    }

    .icon {
        font-size: 80px;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    h1 {
        color: #333;
        font-size: 28px;
        margin: 20px 0;
        font-weight: 600;
    }

    .description {
        color: #666;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .progress-bar {
        background: #e0e0e0;
        height: 8px;
        border-radius: 10px;
        overflow: hidden;
        margin: 20px 0;
    }

    .progress-fill {
        background: linear-gradient(90deg, #667eea, #764ba2);
        height: 100%;
        width: <?= $proggres.'%'; ?>;
        animation: progress 2s ease-in-out infinite;
    }

    @keyframes progress {

        0%,
        100% {
            width: <?= ($proggres - 5).'%'; ?>;
        }

        50% {
            width: <?= $proggres.'%'; ?>;
        }
    }

    .status {
        color: #764ba2;
        font-weight: 600;
        font-size: 14px;
        margin-top: 10px;
    }
</style>

<div class="container-in">
    <div class="icon-wrapper">
        <div class="icon">🔧</div>
    </div>

    <h1>Fitur dalam Pengembangan</h1>

    <p class="description">
        Kami sedang mengerjakan fitur ini untuk memberikan pengalaman terbaik bagi Anda.
        Harap tunggu beberapa saat hingga fitur ini siap digunakan.
    </p>

    <div class="progress-bar">
        <div class="progress-fill"></div>
    </div>

    <p class="status">Progres: <?= $proggres; ?>%</p>
</div>