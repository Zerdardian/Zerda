<div class="form create">
    <form action="/admin/review/create/" method="post">
        <div class="name">
            <label for="name">Review naam</label>
            <input type="text" name="name" id="name" placeholder="name">
        </div>
        <div class="select">
            <label for="type">Kies form type</label>
            <select name="type" id="type">
                <?php
                    foreach($types as $type) {
                        ?>
                        <option value="<?=$type['id']?>"><?=$type['name']?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
        <input type="submit">
    </form>
</div>