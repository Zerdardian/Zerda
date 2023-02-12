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
?>
</body>
</html>