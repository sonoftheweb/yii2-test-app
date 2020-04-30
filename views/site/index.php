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
			<div class="col-lg-6">
				<div class="card">
					<div class="card-header">
						<h3 class="mb-0">Add an Order</h3>
					</div>
					<div class="card-body">
            <?php $form = ActiveForm::begin(); ?>
						<div class="row">
							<div class="col"><?= $form->field($customer, 'first_name')->textInput(['maxlength' => true]) ?></div>
							<div class="col"><?= $form->field($customer, 'last_name')->textInput(['maxlength' => true]) ?></div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($customer, 'email')->textInput(['maxlength' => true]) ?></div>
							<div class="col"><?= $form->field($customer, 'phone_number')->textInput(['maxlength' => true]) ?></div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($order, 'order_type')->dropDownList($order_type) ?></div>
							<div class="col"><?= $form->field($order, 'order_value', [
								'inputTemplate' => '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text font-weight-bold">$</span></div>{input}</div>'
							]) ?></div>
						</div>
						<div class="row">
							<div class="col-6"><?= $form->field($order, 'schedule_date', [
                'inputTemplate' => '<div class="input-group date-picker"><div class="input-group-prepend"><span class="input-group-text"><svg class="bi bi-calendar" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd" d="M14 0H2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V2a2 2 0 00-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z" clip-rule="evenodd"/>
					<path fill-rule="evenodd" d="M6.5 7a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm-9 3a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm-9 3a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
				</svg></span></div>{input}</div>'
							]) ?></div>
						</div>
						<div class="row">
							<div class="col">
                  <?= $form->field($order, 'street_address')->textInput(['maxlength' => true]) ?>
							</div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($order, 'city') ?></div>
							<div class="col"><?= $form->field($order, 'state_province') ?></div>
						</div>
						<div class="row">
							<div class="col"><?= $form->field($order, 'postal_zip_code') ?></div>
							<div class="col"><?= $form->field($order, 'country_id')->dropDownList($countries) ?></div>
						</div>
						<button class="btn btn-outline-secondary">Preview Location</button>
						<button type="submit" class="btn btn-success float-right">Submit</button>
						<button class="btn btn-danger float-right mr-2">Cancel</button>
            <?php $form = ActiveForm::end(); ?>
					</div>
				</div>

				<div class="card mt-3">
					<div class="card-header">
						<h3 class="mb-0">Existing Orders</h3>
					</div>
					<table class="table">
						<thead>
						<tr>
							<th scope="col">First Name</th>
							<th scope="col">Last Name</th>
							<th scope="col">Date</th>
							<th scope="col"></th>
						</tr>
						</thead>
						<tbody>
						<?php
						$statsHtml = '';
						foreach ($statuses as $stat) {
                $statsHtml .= Html::a($stat, ['#'], ['class' => 'dropdown-item']);
            }

						foreach ($orders as $existingOrder){
						?>
						<tr>
							<td><?= $existingOrder['customer']['first_name'] ?></td>
							<td><?= $existingOrder['customer']['last_name'] ?></td>
							<td><?= $existingOrder['schedule_date'] ?></td>
							<td class="text-right">
								<div class="btn-group action-btn" role="group">
									<button type="button" class="btn btn-<?= $existingOrder['status']['color_code'] ?> btn-sm dropdown-toggle btn-block" data-toggle="dropdown">
										<?= strtoupper($existingOrder['status']['name']) ?>
									</button>
									<div class="dropdown-menu">
										<?= $statsHtml ?>
									</div>
								</div>
								<button type="button" class="btn btn-danger btn-circle"<?= (!in_array($existingOrder['status_id'], [1,2])) ? ' disabled' : '' ?>>
									<svg class="bi bi-x" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 010 .708l-7 7a.5.5 0 01-.708-.708l7-7a.5.5 0 01.708 0z" clip-rule="evenodd"/>
										<path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 000 .708l7 7a.5.5 0 00.708-.708l-7-7a.5.5 0 00-.708 0z" clip-rule="evenodd"/>
									</svg>
								</button>
							</td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="card">
					<div class="card-header"><h3 class="mb-0">Map</h3></div>
					<h3 class="mb-0">some map</h3>
				</div>
			</div>
		</div>

	</div>
</div>
