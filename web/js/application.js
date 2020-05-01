$(document).ready(function () {
	$.Application = function () {
		this.map = null;
		this.gecodeField = $('#geocode_me');
		this.datePickerField = $('#order-schedule_date');
		this.table = null;
		this.form = $('#order-form');
		this.ordersUrl = '/orders?expand=status,customer';
		this.orders = [];
		this.doc = $(document);
		this.cancelButton = $('.cancel');
		this.previewLocButton = $('.preview-loc');
		this.previewmarker = null;
		this.mapmarkers = [];

		// keys
		this.goecodekey = '98363dbbe645d524de3a04ce6d4d7d7e';
		this.mapbox = 'pk.eyJ1IjoicHJvbWV0aGV1czYwMDAiLCJhIjoiY2s5bTJxNXFoMmM1ZjNtbncyamh3bW51NSJ9.5YIESgIdFMVqx_Yeq4WE2A';
		this.statusIcons = {};
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

			// additionally if the icon is clicked, then the date picker is shown
			$('.date-picker .input-group-text').on('click', function () {
				self.datePickerField.datepicker('show');
			});

			// watch for geocode field
			this.gecodeField.autoComplete({
				resolver: 'custom',
				events: {
					search: function (qry, callback) {
						$('.loading-geo').show();
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
							$('.loading-geo').hide();
							callback(data);
						});
					}
				}
			});

			// watch for on select on geocode field
			this.gecodeField.on('autocomplete.select', function (evt, item) {
				// set the city and state / province
				$('#order-city').val(item.county);
				$('#order-state_province').val(item.region);
				$('#order-postal_zip_code').val((item.hasOwnProperty('zip_code')) ? item.zip_code : item.postal_code);
				$('#latitude').val(item.latitude);
				$('#longitude').val(item.longitude);
			});

			// check if we are typing in the location field
			this.gecodeField.keyup(function () {
				if (self.previewmarker)
					self.map.removeLayer(self.previewmarker);
			})

			// submit the form via ajax
			this.form.on('beforeSubmit', function () {
				let data = self.form.serialize();
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
					data: {status_id: status_id},
					success: function (data) {
						self.loadTable(true);
						self.triggerAlert('success', 'Saved orders successfully.');
					},
					error: function (jQueryXHR, error) {
						self.triggerAlert('error', 'Something went kaboom! Get IT in here!');
					}
				});
			})

			this.cancelButton.on('click', function (event) {
				event.preventDefault();
				self.form[0].reset()
			});

			this.doc.on('click', 'button.delete-order', function (event) {
				let id = $(this).data('order_id');

				if ($(this)[0].hasAttribute('disabled')) // you shall not pass!!!
					return;

				self.confirmAction('Listen are you sure about this? I ain\'t no magician and cannot bring this back!', function (resp) {
					if (resp) {
						// send this to the server via ajax
						$.ajax({
							url: '/orders/' + id,
							type: 'DELETE',
							success: function (data) {
								self.loadTable(true);
								self.triggerAlert('success', 'Deleted order.');
							},
							error: function (jQueryXHR, error) {
								self.triggerAlert('error', 'How do you keep your cool? You should be screaming right now...');
							}
						});
					}
				});
			});

			// watch for if the preview button is clicked
			this.previewLocButton.on('click', function (event) {
				event.preventDefault();
				let longitude = self.form.find('#longitude').val(),
					latitude = self.form.find('#latitude').val(),
					street_address = self.form.find('#geocode_me').val();

				if (longitude && latitude) {
					self.previewmarker = new L.marker([latitude, longitude], {icon: self.statusIcons['pending']})
						.addTo(self.map)
						.bindPopup(`<b>Preview: ${street_address}</b>`).openPopup();
				}
			});
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

					// create a table
					let tableSelector = $('#orders-table');
					self.table = tableSelector.DataTable({
						initComplete: function () {
							self.map.on('popupopen', function (ev) {
								let id = $(ev.popup._source._popup._wrapper).find('.popup-order_id');
								if (!id) return false;
								id = id.text()

								// find the action button with data-order_id = id
								let datatable = $('.order_list_table');
								datatable.find('tr').removeClass('highlighted');
								if (!id) return false;
								datatable.find('tr[data-order_row_id=' + id + ']').addClass('highlighted')
							});
						},
						createdRow: function (row, data, dataIndex) {
							$(row).attr('data-order_row_id', data.id); // adds data attr to the table
						},
						scrollY: "280px",
						scrollCollapse: true,
						paging: false,
						searching: false,
						destroy: true,
						data: self.orders,
						columnDefs: [{
							targets: [3],
							orderable: false
						}],
						columns: [
							{data: 'first_name'},
							{data: 'last_name'},
							{data: 'schedule_date'},
							{
								data: function (data) {
									return self.tableActionHTML(data);
								}
							}
						]
					});

					// if any row of the table is clicked, open popup in map
					tableSelector.find('tbody').on('click', 'tr', function () {
						let data = self.table.row( this ).data(),
						filteredMarker = self.mapmarkers.filter(function (marker) {
							return data.id === marker.id;
						});
						if (!filteredMarker.length) return false;
						filteredMarker[0].marker.openPopup();
					});

				}, error(jQueryXHR, error) {
					self.triggerAlert('error', 'Unable to load orders table. You can go yell at the dev\'s now...');
				}
			})
		},
		setIconSet: function () {
			let IconSet = L.Icon.extend({
				options: {
					iconSize: [30, 30],
					iconAnchor: [15, 15],
					popupAnchor: [0, -15]
				}
			});

			this.statusIcons = {
				pending: new IconSet({iconUrl: '/logistics/057-stopwatch.png'}),
				assigned: new IconSet({iconUrl: '/logistics/005-calendar.png'}),
				on_route: new IconSet({iconUrl: '/logistics/028-express-delivery.png'}),
				done: new IconSet({iconUrl: '/logistics/015-delivered.png'}),
				cancelled: new IconSet({iconUrl: '/logistics/016-delivery-failed.png'})
			};
		},
		initMap: function () {
			// we could just redraw the thing, but where's the fun in that
			$('.map-container').html('<div id="map"></div>');

			// went with mapbox, seems more fun to implement
			this.map = L.map('map').setView([0, 0], 13);
			L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=' + this.mapbox, {
				attribution: '',
				maxZoom: 18,
				id: 'mapbox/streets-v11',
				tileSize: 512,
				zoomOffset: -1,
				accessToken: this.mapbox
			}).addTo(this.map);

			// initialize icon sets
			this.setIconSet();

			if (this.orders && this.orders.length) {
				$.each(this.orders, function (index, value) {
					let order_type = value.order_type[0].toUpperCase() + value.order_type.slice(1);
					self.mapmarkers.push({
						id: value.id,
						marker: L.marker([value.latitude, value.longitude], {icon: self.statusIcons[value.status.tag]})
							.addTo(self.map)
							.bindPopup(`<span class="d-none popup-order_id">${value.id}</span><b>${order_type + ':  ' + value.street_address}</b><br/> For ${value.customer.first_name + ' ' + value.customer.last_name}.`)
					});
				});
				this.map.fitBounds(this.orders.map(order => [order.latitude, order.longitude]));
			}
		},
		tableActionHTML: function (order) {
			let status = order.status,
				statusList = $('.status-template').html(),
				cancelDisabled = (![1, 2].includes(order.status_id)) ? ' disabled' : '';

			return '<div class="btn-group action-btn" data-order_id="' + order.id + '" role="group">' +
				'<button type="button" class="btn btn-' + status.color_code + ' btn-sm dropdown-toggle btn-block" data-toggle="dropdown">' +
				status.name +
				'</button>' +
				'<div class="dropdown-menu" data-order_id="' + order.id + '">' + statusList + '</div>' +
				'</div>' +
				'<button type="button" data-order_id="' + order.id + '" class="ml-3 btn btn-danger btn-circle delete-order"' + cancelDisabled + '>' +
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
		},
		confirmAction: function (message, callback) {
			$(`<div class="modal fade" id="confirmModal" role="dialog"> 
	     <div class="modal-dialog modal-sm">
	        <div class="modal-content"> 
	           <div class="modal-body" style="padding:10px;"> 
	             <div>
									${message} 
								</div>  
								<div class="mt-3">
									<button type="button" class="btn btn-secondary btn-sm not-confirmed float-left" data-dismiss="modal">Err.. No.</button>
									<button type="button" class="btn btn-primary btn-sm confirmed float-right">Do it!</button>
								</div> 
	           </div> 
	       </div> 
	    </div> 
	  </div>`).appendTo('body');

			let modalDialog = $('#confirmModal');

			// Trigger the modal
			modalDialog.modal({
				backdrop: 'static'
			});

			$(".confirmed").click(function () {
				callback(true);
				modalDialog.modal("hide");
			});

			//Pass false to callback function
			$(".not-confirmed").click(function () {
				callback(false);
				modalDialog.modal("hide");
			});

			//Remove the modal once it is closed.
			modalDialog.on('hidden.bs.modal', function () {
				modalDialog.remove();
			});
		}
	}

	let applicationObj = new $.Application();
	applicationObj.init();

});