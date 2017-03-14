<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Who Is Online?</h1>
        <h2 class="h5 text-white-op animated zoomIn"></h2>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">

            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">onlinelist</h3>
                </div>
                <div class="block-content">
                    <table class="table">
                        <tr>
                            <th>Name</th>
                            <th>Guild</th>
                            <th>Level</th>
                            <th>Vocation</th>
                        </tr>
                        <?php if ($array = online_list()): ?>
                            <?php foreach ($array as $value): ?>
                                <tr class="special">
                                <td>
                                    <a href="?subtopic=characterprofile&name=<?php echo sanitize($value['name']); ?>"><?php echo sanitize($value['name']); ?></a>
                                </td>
                                <?php if (!empty($value['gname'])): ?>
                                    <td><a href="/?subtopic=guilds&name=<?php echo sanitize($value['gname']); ?>"><?php echo sanitize($value['gname']); ?></a></td>
                                <?php endif; ?>
                                <td><?php echo $value['level']; ?></td>
                                <td><?php echo vocation_id_to_name($value['vocation']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">Nobody is online.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

