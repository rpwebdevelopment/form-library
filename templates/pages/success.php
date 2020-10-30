<h1>Form Submitted Successfully</h1>

<p>
    Thank you for your submission, we have received the below information
    from you successfully!
</p>

<table class="table">
    <thead>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach (self::$content as $title => $value) {
        ?>
        <tr>
            <td><?= ucwords(str_replace('_', ' ', $title)) ?></td>
            <td><?= $value ?></td>
        </tr>
        <?php
    }
?>
    </tbody>
</table>
