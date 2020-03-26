<!DOCTYPE html>
<html lang="en">
<?php
/**
 * Template Name: FAQ page
 */
wp_head(); ?>
<body>
<div class="wrapper innerPageHeader">

    <?php get_header(); ?>
    <section class="innerPageSection faqPageSection">
        <div class="content">
            <div class="titleWrap innerPageTitleWrap">
                <div class="h2"><?php _e('Часто задаваемые вопросы', 'traveling-store'); ?></div>
            </div>
            <div class="textPageContent">
                <div class="faqBlock">
                    <div class="faqTitle h4">
                        <?php _e('Реально ли получить качественную экскурсию без участия уличных агентств, и отельного гида?', 'traveling-store'); ?>
                    </div>
                    <div class="faqText p">
                        <?php _e('Такие экскурсионные объекты как Дайвинг, Квадросафари, СПА-Хамам, Яхта, Динопарк и им подобные
                        исполняют сами весь комплекс программы: трансфер, инструктора, гиды, питание и др. Ко всему
                        этому вся гарантия и ответственность возлагается на самого исполнителя тура. Поэтому в
                        организации таких туров нет необходимости в посредниках в образе туроператора или уличного
                        агенства. Наличие их лишь увеличивает стоимость тура, никак не влияя на его качество.', 'traveling-store'); ?>
                    </div>
                </div>
                <div class="faqBlock">
                    <div class="faqTitle h4">
                        <?php _e('В чём отличие вашего сервиса от услуг туроператора и уличных агенств?', 'traveling-store'); ?>
                    </div>
                    <div class="faqText p">
                        <?php _e('Являясь контактным центром туристических объектов мы не занимается продажей туров. Traveling
                        Store занимается обработкой заявок, подтверждением и консультативным сопровождением до окончания
                        тура. Traveling Store является площадкой принципа "Заказчик-Испрлнитель". Все другие агенства
                        являются посредниками между заказчиком и исполнителем,в результате чего цена услуг существенно
                        увеличивается.', 'traveling-store'); ?>
                    </div>
                </div>
                <div class="faqBlock">
                    <div class="faqTitle h4">
                        <?php _e('На чем Вы зарабатываете?', 'traveling-store'); ?>
                    </div>
                    <div class="faqText p">
                        <?php _e('Мы работаем по принципу контактного центра и не принимаем оплаты услуг от туристов.
                        Незначительная оплата услуг контактного центра ложиться на исполнителя экскурсии.', 'traveling-store'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php get_footer(); ?>

</div>
</body>
<?php wp_footer(); ?>
</html>