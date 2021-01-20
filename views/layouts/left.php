<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/default-member.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= \app\src\helpers\UserHelper::getLogoUsername(); ?></p>

                <a href="#" class="hide"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
<!--        <form action="#" method="get" class="sidebar-form">-->
<!--            <div class="input-group">-->
<!--                <input type="text" name="q" class="form-control" placeholder="Search..."/>-->
<!--              <span class="input-group-btn">-->
<!--                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>-->
<!--                </button>-->
<!--              </span>-->
<!--            </div>-->
<!--        </form>-->
        <!-- /.search form -->

      <?php $currentUser = \app\src\entities\user\User::find()->andWhere(['id'=>Yii::$app->user->getId()])->one();
      echo $this->render('menu'); ?>

    </section>

</aside>
