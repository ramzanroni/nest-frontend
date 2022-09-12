<?php
if ($_POST['check'] == "menuBarOpen") {
    $catId = $_POST['catId'];
?>

    <h5 class="section-title style-1 mb-30">Category <?php echo $catId = $_POST['catId']; ?></h5>
    <ul>
        <li>
            <a onmouseover="getOtherMenu1(1)" href="#"> <img src="" alt="" />Test1</a><span class="count">1</span>
        </li>
        <li>
            <a onmouseover="getOtherMenu1(2)" href="#"> <img src="" alt="" />Test1</a><span class="count">2</span>
        </li>
        <li>
            <a onmouseover="getOtherMenu1(3)" href="#"> <img src="" alt="" />Test1</a><span class="count">3</span>
        </li>
        <li>
            <a onmouseover="getOtherMenu1(4)" href="#"> <img src="" alt="" />Test1</a><span class="count">4</span>
        </li>
        <li>
            <a onmouseover="getOtherMenu1(5)" href="#"> <img src="" alt="" />Test1</a><span class="count">5</span>
        </li>
        <li>
            <a onmouseover="getOtherMenu1(6)" href="#"> <img src="" alt="" />Test1</a><span class="count">5</span>
        </li>
    </ul>
<?php
}
if ($_POST['check'] == 'menuBarOpen1') {
    $catId = $_POST['catId'];
?>

    <h5 class="section-title style-1 mb-30">Category <?php echo $catId = $_POST['catId']; ?></h5>
    <ul>
        <li>
            <a href="#"> <img src="" alt="" />Test1</a><span class="count">1</span>
        </li>
        <li>
            <a href="#"> <img src="" alt="" />Test1</a><span class="count">2</span>
        </li>
        <li>
            <a href="#"> <img src="" alt="" />Test1</a><span class="count">3</span>
        </li>
        <li>
            <a href="#"> <img src="" alt="" />Test1</a><span class="count">4</span>
        </li>
        <li>
            <a href="#"> <img src="" alt="" />Test1</a><span class="count">5</span>
        </li>
        <li>
            <a href="#"> <img src="" alt="" />Test1</a><span class="count">5</span>
        </li>
    </ul>
<?php
}
