<h1>Список записей</h1>

<section>
    <ul>
        <?php
        foreach ($posts as $post) {
            ?>
            <li>
                <h2><a href="/framework/posts/<?php echo $post->getId();?>"><?php echo $post->getTitle();?></a></h2>
                <p><?php echo $post->getContent();?></p>
            </li>
        <?php
        }
        ?>
    </ul>
</section>