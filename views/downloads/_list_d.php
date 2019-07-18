<div align="center" class="f_items">
    <img src="/downloads/img/<?= $model[ 'img' ] ?>" width="150" border="0">
    <a href="/downloads/<?= $model[ 'patch' ] ?>"><?= $model[ 'name' ]; ?></a>
    <p class="h_tag">
        <?
        foreach ( $model[ 'filetagall' ] as $t )
            {
            //$getParams = array_merge($_GET, ['hash_id' => $t['tag_id']]);
            $getParams = [ 'hash_id' => $t[ 'tag_id' ] ];
            $url_this  = Yii::$app->urlManager->createUrl( array_merge( [ Yii::$app->requestedRoute ], $getParams ) );
            echo '<a href="' . $url_this . '">#' . $hash_tag_array[ $t[ 'tag_id' ] ] . '</a> ';
            }
        ?>
    </p>
</div>