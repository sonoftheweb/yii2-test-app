<?php

/* @var $this yii\web\View */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Alert;
use yii\bootstrap4\Html;


/* @var $customer */
/* @var $order */
/* @var $orders */
/* @var $order_type */
/* @var $countries */
/* @var $statuses */

$this->title = 'Cigo Interview';
?>
<div class="site-index">

	<div class="body-content">
		<?php
		$flash = Yii::$app->session->getFlash('orders');
		if (!empty($flash)) {
        echo Alert::widget([
            'options' => ['class' => 'alert-info'],
            'body' => $flash,
        ]);
		}?>
		<div class="row">
			<div class="col-lg-6 first-col">
				<div class="card">
					<div class="card-header">
						<h3 class="mb-0 float-left">Add an Order</h3>
						<i class="fas fa-2x fa-address-card float-right"></i>
					</div>
					<div class="card-body">
            <?php $form = ActiveForm::begin(['id' => 'order-form', 'options' => ['autocomplete' => 'off']]); ?>
						<div class="row">
							<div class="col"><?= $form->field($customer, 'first_name')->textInput(['placeholder' => 'First Name', 'autocomplete' => rand(1, 100)]) ?></div>
							<div class="col"><?= $form->field($customer, 'last_name')->textInput(['placeholder' => 'Last Name', 'autocomplete' => rand(1, 100)]) ?></div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($customer, 'email')->textInput(['placeholder' => 'you@sample.com', 'autocomplete' => rand(1, 100)]) ?></div>
							<div class="col"><?= $form->field($customer, 'phone_number')->textInput(['placeholder' => '+1 (888) 123-4567', 'autocomplete' => rand(1, 100)]) ?></div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($order, 'order_type')->dropDownList($order_type) ?></div>
							<div class="col"><?= $form->field($order, 'order_value', [
								'inputTemplate' => '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>{input}</div>'
							])->textInput(['placeholder' => 'Amount', 'autocomplete' => rand(1, 100)]) ?></div>
						</div>
						<div class="row">
							<div class="col-6"><?= $form->field($order, 'schedule_date', [
                'inputTemplate' => '<div class="input-group date-picker"><div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i></span></div>{input}</div>'
							])->textInput(['placeholder' => date('Y-m-d'), 'autocomplete' => rand(1, 100)]) ?></div>
						</div>
						<div class="row">
							<div class="col">
                <?= $form->field($order, 'street_address')->textInput(['id' => 'geocode_me', 'autocomplete' => rand(1, 100)]) ?>
                <div class="loading-geo">loading...</div>
								<?= $form->field($order, 'latitude', [
								    'template' => '{input}',
								])->hiddenInput(['id' => 'latitude']); ?>
                <?= $form->field($order, 'longitude', [
                    'template' => '{input}',
                ])->hiddenInput(['id' => 'longitude']); ?>
							</div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($order, 'city')->textInput(['autocomplete' => rand(1, 100)]) ?></div>
							<div class="col"><?= $form->field($order, 'state_province')->textInput(['autocomplete' => rand(1, 100)]) ?></div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($order, 'postal_zip_code')->textInput(['autocomplete' => rand(1, 100)]) ?></div>
							<div class="col"><?= $form->field($order, 'country_id')->dropDownList($countries) ?></div>
						</div>
						<button class="btn btn-outline-secondary preview-loc">Preview Location</button>
						<button type="submit" class="btn btn-success float-right">Submit</button>
						<button class="cancel btn btn-danger float-right mr-2">Cancel</button>
            <?php $form = ActiveForm::end(); ?>
					</div>
				</div>

				<div class="card mt-3">
					<div class="card-header">
						<h3 class="mb-0 float-left">Existing Orders</h3>
						<i class="far fa-2x fa-check-square float-right"></i>
					</div>

					<div class="d-none status-template">
            <?php
	            $statsHtml = '';
	            foreach ($statuses as $key => $stat) {
	                $statsHtml .= Html::a($stat, ['#'], ['class' => 'dropdown-item change-status', 'data-status_id' => $key]);
	            }
	            echo $statsHtml;
            ?>
					</div>

					<table id="orders-table" class="table order_list_table">
						<thead>
						<tr>
							<th scope="col">First Name</th>
							<th scope="col">Last Name</th>
							<th scope="col">Date</th>
							<th scope="col"></th>
						</tr>
						</thead>
					</table>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<div class="card-header">
						<h3 class="mb-0 float-left">Map</h3>
						<i class="fas fa-2x fa-globe-americas float-right"></i>
					</div>
					<div class="map-container"></div>
				</div>
			</div>
		</div>

	</div>
</div>
