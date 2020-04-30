$(document).ready(function(){
	$.Application = function (){
		this.map = null;
		this.gecodeField = $('#geocode_me');
		this.datePickerField = $('#order-schedule_date');
		this.table = $('#orders-table');
		this.form = $('#order-form');
		this.ordersUrl = '/orders?expand=status,customer';
		this.orders = [];
		this.doc = $(document);
		this.previewLocButton = $('.preview-loc');

		// keys
		this.goecodekey = '98363dbbe645d524de3a04ce6d4d7d7e';
	}

	$.Application.prototype = {
		init: function () {
			let self = this;

			// load the table with orders which should indirectly load the map
			this.loadTable(true);

			// init date picker
			this.datePickerField.datepicker({
				format: 'yyyy-mm-dd'
			});

			// watch for geocode field
			this.gecodeField.autoComplete({
				resolver: 'custom',
				events: {
					search: function (qry, callback) {
						$.ajax({
							url: 'http://api.positionstack.com/v1/forward',
							data: {
								access_key: self.goecodekey,
								query: qry,
								limit: 5
							}
						}).done(function (res) {
							let data = res.data.map((d) => {
								d.text = d.label;
								return d;
							})
							callback(data);
						});
					}
				}
			});

			// watch for on select on geocode field
			this.gecodeField.on('autocomplete.select', function(evt, item) {
				// set the city and state / province
				$('#order-city').val(item.county);
				$('#order-state_province').val(item.region);
				$('#order-postal_zip_code').val((item.hasOwnProperty('zip_code')) ? item.zip_code : item.postal_code);
				$('#latitude').val(item.latitude);
				$('#longitude').val(item.longitude);
			});

			// submit the form via ajax
			this.form.on('beforeSubmit', function () {
				let data = form.serialize();
				self.form[0].reset();
				$.ajax({
					url: self.form.attr('action'),
					type: 'POST',
					data: data,
					success: function (data) {
						self.loadTable(true);
						self.triggerAlert('success', 'Saved orders successfully.');
					},
					error: function (jQueryXHR, error) {
						self.triggerAlert('error', 'Something went kaboom! Get IT in here!');
					}
				});
				return false;
			});

			// watch for change in status per order row
			this.doc.on('click', 'a.change-status', function (event) {
				event.preventDefault();
				let status_id = $(this).data('status_id'),
					id = $(this).parent().data('order_id');

				// send this to the server via ajax
				$.ajax({
					url: '/orders/' + id,
					type: 'PUT',
					data: { status_id: status_id },
					success: function (data) {
						self.loadTable(true);
						self.triggerAlert('success', 'Saved orders successfully.');
					},
					error: function (jQueryXHR, error) {
						self.triggerAlert('error', 'Something went kaboom! Get IT in here!');
					}
				});
			})

			// watch for if the preview button is clicked
			this.previewLocButton.on('click', function (event) {
				event.preventDefault();
				let longitude = form.find('#longitude').val(),
					latitude = form.find('#latitude').val();

				if (longitude && latitude) {

				}
			})
		},
		loadTable: function (reload_map) {
			self = this;
			$.ajax({
				url: this.ordersUrl,
				type: 'GET',
				success: function (data) {
					self.orders = data.map(function (d) {
						d.first_name = d.customer.first_name;
						d.last_name = d.customer.last_name;
						return d;
					});

					// load map if desired
					if (reload_map) {
						self.initMap();
					}

					self.table.DataTable({
						destroy: true,
						data: self.orders,
						columnDefs: [{
							targets: [3],
							orderable: false
						}],
						columns: [
							{ data: 'first_name' },
							{ data: 'last_name' },
							{ data: 'schedule_date' },
							{ data: function (data) {
									return self.tableActionHTML(data);
							}}
						]
					});
				}, error(jQueryXHR, error) {
					self.triggerAlert('error', 'Unable to load orders table. You can go yell at the dev\'s now...');
				}
			})
		},
		initMap: function () {
			// we could just redraw the thing, but where's the fun in that
			$('.map-container').html('<div id="map"></div>');

			this.map = L.map('map').setView([0, 0], 13);
			L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoicHJvbWV0aGV1czYwMDAiLCJhIjoiY2s5bTJxNXFoMmM1ZjNtbncyamh3bW51NSJ9.5YIESgIdFMVqx_Yeq4WE2A', {
				attribution: '',
				maxZoom: 18,
				id: 'mapbox/streets-v11',
				tileSize: 512,
				zoomOffset: -1,
				accessToken: 'pk.eyJ1IjoicHJvbWV0aGV1czYwMDAiLCJhIjoiY2s5bTJxNXFoMmM1ZjNtbncyamh3bW51NSJ9.5YIESgIdFMVqx_Yeq4WE2A'
			}).addTo(this.map);

			let IconSet = L.Icon.extend({
				options: {
					iconSize: [30, 30],
					iconAnchor: [15, 15],
					popupAnchor: [0, -15]
				}
			});
			let statusIcons = {
				pending: new IconSet({iconUrl: '/logistics/057-stopwatch.png'}),
				assigned: new IconSet({iconUrl: '/logistics/005-calendar.png'}),
				on_route: new IconSet({iconUrl: '/logistics/028-express-delivery.png'}),
				done: new IconSet({iconUrl: '/logistics/015-delivered.png'}),
				cancelled: new IconSet({iconUrl: '/logistics/016-delivery-failed.png'})
			};

			if (this.orders && this.orders.length) {
				$.each(this.orders, function (index, value) {
					L.marker([value.latitude, value.longitude], {icon: statusIcons[value.status.tag]})
						.addTo(self.map)
						.bindPopup(`<b>${value.street_address}</b><br/> For ${value.customer.first_name + ' ' + value.customer.last_name}.`);
				});
				this.map.fitBounds(this.orders.map(order => [order.latitude, order.longitude]));
			}
		},
		tableActionHTML: function (order) {
			let status = order.status,
				statusList = $('.status-template').html(),
				cancelDisabled = (![1,2].includes(order.status_id)) ? ' disabled' : '';

			return '<div class="btn-group action-btn" role="group">' +
				'<button type="button" class="btn btn-' + status.color_code + ' btn-sm dropdown-toggle btn-block" data-toggle="dropdown">' +
				status.name +
				'</button>' +
				'<div class="dropdown-menu" data-order_id="' + order.id + '">' + statusList + '</div>' +
				'</div>' +
				'<button type="button" class="ml-3 btn btn-danger btn-circle"' + cancelDisabled + '>' +
				'<i class="fas fa-times"></i>' +
				'</button>';
		},
		triggerAlert: function (type, message) {
			let alert = $('.alert');
			alert.addClass('alert-' + type);
			alert.find('.message').html(message);

			alert.show();
			setTimeout(function () {
				alert.hide();
				alert.removeClass('alert-' + type);
			}, 5000);
		}
	}

	let applicationObj = new $.Application();
	applicationObj.init();

});