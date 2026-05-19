<?= $this->extend('layout/dashboard_layout') ?>
<?= $this->section('content') ?>

<div class="page-heading">
    <h1><i class="fas fa-gamepad"></i> Dashboard Admin Game</h1>
    <p>Selamat datang, <?= esc(session('nama_lengkap')) ?></p>
</div>

<div class="row">
    <div class="col-12">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-gamepad"></i></div>
            <div>
                <div class="stat-label">Total Game Dikelola</div>
                <div class="stat-value"><?= $total_game ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>Data Game Terbaru</h4>
        <a href="<?= base_url('game/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Game</a>
    </div>
    <div class="card-body-inner" style="padding:0">
        <table class="tbl">
            <thead>
                <tr><th>Logo</th><th>Nama Game</th><th>Slug</th></tr>
            </thead>
            <tbody>
                <?php if (empty($game_terbaru)): ?>
                <tr><td colspan="3" class="empty-state"><i class="fas fa-gamepad"></i><br>Belum ada game</td></tr>
                <?php else: ?>
                    <?php foreach ($game_terbaru as $g): ?>
                    <tr>
                        <td>
                            <?php if ($g['logo']): ?>
                                <img src="<?= base_url('uploads/game/'.$g['logo']) ?>" width="40" height="40" style="object-fit:cover;border-radius:5px;">
                            <?php else: ?>
                                <div style="width:40px; height:40px; background:#444; border-radius:5px; display:flex; align-items:center; justify-content:center; color:#888;">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= esc($g['nama_game']) ?></strong></td>
                        <td><?= esc($g['slug']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>