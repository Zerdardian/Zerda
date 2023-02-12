</div>
</div>
<footer id="footer" class="footer">
</footer>
<script src="/assets/js/basis.js"></script>
<?php
    if(!empty($_SESSION['page'][1]) && file_exists("./assets/js/".$_SESSION['page'][1].".js")) {
        ?>
        <script src="/assets/js/<?=$_SESSION['page'][1]?>.js"></script>
        <?php
    }

    if(!empty($_SESSION['page'][3]) && $_SESSION['page'][1] == 'admin' && $_SESSION['page'][2] == 'review' && $_SESSION['page'][3] == 'edit') {
        ?>
        <script src="/assets/js/admin/review/edit.js"></script>
        <?php
    }
?>
</body>
</html>