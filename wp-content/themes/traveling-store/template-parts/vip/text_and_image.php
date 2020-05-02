<div class="textPageContentRow">
    <div class="leftSide textSide">
        <div class="p semibold">
            <?php echo $vip_item['title'];?>
        </div>
        <div class="p grey">
            <?php echo $vip_item['text']; ?>
        </div>
    </div>
    <div class="rightSide picSide tour1">
        <?php echo wp_get_attachment_image($vip_item['image']['ID'], array(780, 280), false); ?>
    </div>
</div>