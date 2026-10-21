<main class="changelog-page">
    <header class="changelog-hero">
        <div class="changelog-hero__copy">
            <span class="changelog-eyebrow"><i class="fa fa-github" aria-hidden="true"></i> CATATAN PENGEMBANGAN</span>
            <h1>Jejak perubahan</h1>
            <p>Rangkaian pembaruan yang membentuk DSP+, langsung dari repositori pengembangan.</p>
        </div>
        <a class="changelog-repo-link" href="https://github.com/muhammadnorseputra/renkeu" target="_blank" rel="noopener noreferrer">
            <i class="fa fa-github" aria-hidden="true"></i>
            <span>muhammadnorseputra/<strong>renkeu</strong></span>
            <i class="fa fa-external-link" aria-hidden="true"></i>
        </a>
    </header>

    <?php if ($error !== null): ?>
        <div class="changelog-alert" role="alert">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
            <div>
                <strong>Changelog belum dapat dimuat</strong>
                <p><?= html_escape($error) ?></p>
            </div>
        </div>
    <?php elseif (empty($commits)): ?>
        <div class="changelog-empty">
            <span class="changelog-empty__icon"><i class="fa fa-code-fork" aria-hidden="true"></i></span>
            <h2>Belum ada catatan perubahan</h2>
            <p>Commit terbaru akan muncul di sini saat tersedia.</p>
        </div>
    <?php else: ?>
        <div class="changelog-summary">
            <span class="changelog-summary__pulse" aria-hidden="true"></span>
            Menampilkan <strong><?= count($commits) ?></strong> commit terbaru
        </div>

        <div class="changelog-timeline">
            <?php $activeMonth = null; ?>
            <?php foreach ($commits as $commit): ?>
                <?php if ($activeMonth !== $commit['month_key']): ?>
                    <?php if ($activeMonth !== null): ?>
                        </ol>
                    </section>
                    <?php endif; ?>
                    <?php $activeMonth = $commit['month_key']; ?>
                    <section class="changelog-period" aria-label="<?= html_escape($commit['month_label']) ?>">
                        <h2 class="changelog-period__title"><?= html_escape($commit['month_label']) ?></h2>
                        <ol class="changelog-entries">
                <?php endif; ?>

                <li class="changelog-entry">
                    <span class="changelog-entry__marker" aria-hidden="true"><i class="fa fa-code" aria-hidden="true"></i></span>
                    <article class="changelog-card">
                        <div class="changelog-card__meta">
                            <time datetime="<?= html_escape($commit['date_iso']) ?>">
                                <i class="fa fa-calendar-o" aria-hidden="true"></i>
                                <?= html_escape($commit['date_label']) ?>
                                <span><?= html_escape($commit['time_label']) ?></span>
                            </time>
                            <?php if ($commit['url'] !== ''): ?>
                                <a class="changelog-sha" href="<?= html_escape($commit['url']) ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="fa fa-code-fork" aria-hidden="true"></i>
                                    <?= html_escape($commit['short_sha']) ?>
                                </a>
                            <?php else: ?>
                                <span class="changelog-sha"><?= html_escape($commit['short_sha']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= html_escape($commit['subject']) ?></h3>
                        <?php if ($commit['description'] !== ''): ?>
                            <p class="changelog-card__description"><?= nl2br(html_escape($commit['description'])) ?></p>
                        <?php endif; ?>
                        <div class="changelog-card__author">
                            <span class="changelog-author-avatar" aria-hidden="true"><i class="fa fa-user"></i></span>
                            <span><?= html_escape($commit['author']) ?></span>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
            </ol>
        </section>
        </div>
    <?php endif; ?>
</main>
