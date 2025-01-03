<?php
   require_once '_config.php';
   auth_check();

   $title = T::add .' '. T::booking;
   include "_header.php";

   if($_POST){
    dd($_POST);
   }

//    $db->insert("hotels_bookings", [
//        "hotel_id" => 1,
//        "room_id" => 1,
//        "room_option_id" => 1,
//        "checkin" => "2022-12-12",
//        "checkout" => "2022-12-15",
//        "adults" => 2,
//        "childs" => 1,
//        "price" => 330,
//        "first_name" => "John",
//        "last_name" => "Doe",
//        "email" => "",
//    ]);

    $id = $db->id();
    if (isset($id)) {
        // $_SESSION['booking_inserted'] = true;
    }

   ?>


<div class="page_head bg-transparent">
   <div class="panel-heading px-5">
      <div class="float-start">
         <p class="m-0 page_title">
            <?=T::add.' '.T::booking?>
         </p>
      </div>
      <div class="float-end">
         <a href="javascript:window.history.back();" data-toggle="tooltip" data-placement="top" title="Previous Page"
            class="loading_effect btn btn-warning">
         <?=T::back?>
         </a>
      </div>
   </div>
</div>
<div class="container">
    <?php
    if (isset($_SESSION['booking_inserted'])){ ?>
    <p class="alert alert-success"> Your booking has been added to system</p>
    <?php }
    unset($_SESSION['booking_inserted']);
    ?>
</div>
<div class="mt-1">
   <div class="p-3">
      <div class="container px-5">
         <form method="post" action="<?=root?>booking-add.php">
            <!-- Select Hotel -->
            <div class="row g-3 mb-3">
               <div class="col-md-3">
                  <?php
                     $locations = $db->select("hotels", "location", ["status" => 1, "GROUP" => "location"]);
                     ?>
                  <div class="">
                     <select class="select2" id="locationSelect" name="location" required>
                        <option value="" disabled selected>Select a Location</option>
                        <?php foreach($locations as $location) { ?>
                        <option value="<?= $location ?>">
                           <?= $location ?>
                        </option>
                        <?php } ?>
                     </select>
                     <!-- <label for="locationSelect">Select Location</label> -->
                  </div>
               </div>
               <div class="col-md-3">
                  <div class=" ">
                     <select class="select2" id="hotelSelect" name="hotel" required>
                        <option value="" disabled selected>Select a Hotel</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class=" ">
                     <select class="select2" id="roomSelect" name="room" required>
                        <option value="" disabled selected>Select a Room</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class=" ">
                     <select class="select2" id="roomOption" name="room_option" required>
                        <option value="" disabled selected>Select a Room</option>
                     </select>
                  </div>
               </div>
            </div>
            <!-- Check-in and Check-out Dates -->
            <div class="row g-3 mb-3">
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="checkin form-control" id="" name="checkin"  autocomplete="off" required value="<?php $d=strtotime("+3 Days"); echo date("d-m-Y", $d); ?>">
                     <label for="checkinDate">Check-in Date</label>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="checkout form-control" id="" name="checkout" required autocomplete="off" value="<?php $d=strtotime("+4 Days"); echo date("d-m-Y", $d); ?>">
                     <label for="checkoutDate">Check-out Date</label>
                  </div>
               </div>

            </div>
            <!-- Number of Travelers -->
            <!-- <div class="row mb-3 g-3">
               <div class="col-md-4">
                   <div class="form-floating">
                       <input type="number" class="form-control" id="numTravelers" name="travelers" min="1"
                           value="1" required>
                       <label for="numTravelers">Number of Travelers</label>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="form-floating">
                       <input type="number" class="form-control" id="numAdults" name="adults" min="1" value="1"
                           required>
                       <label for="numAdults">Adults</label>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="form-floating">
                       <input type="number" class="form-control" id="numChildren" name="childs" min="0" value="0">
                       <label for="numChildren">Children</label>
                   </div>
               </div>
               </div> -->
            <!-- Booking Price -->
            <!-- Client Details -->
            <div class="row mb-3 g-3">
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="form-control" id="firstName" name="first_name" placeholder="Enter first name" required>
                     <label for="firstName">First Name</label>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="form-control" id="lastName" name="last_name" placeholder="Enter last name" required>
                     <label for="lastName">Last Name</label>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="email" class="form-control" id="clientEmail" name="email"
                        placeholder="Enter email address" required>
                     <label for="clientEmail">Email</label>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="number" class="form-control" id="clientPhone" name="phone"
                        placeholder="Enter Phone Number" required>
                     <label for="clientPhone">Phone</label>
                  </div>
               </div>
            </div>
            <div class="card mb-2">
               <div class="card-header bg-primary text-dark">
                  <strong class="">
                  <?=T::travellers?>
               </div>
               <div class="card-body p-3">
                  <p class="mb-2"><strong>Adults</strong></p>
                  <div class="adults-container text-center">
                     <div class="row adults_clone mt-3">
                        <div class="col-md-2">
                           <div class="form-floating">
                              <select name="adults_data[0][title]" class="form-select">
                                 <option value="Mr">Mr</option>
                                 <option value="Miss">Miss</option>
                                 <option value="Mrs">Mrs</option>
                              </select>
                              <label for="">Title</label>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-floating">
                              <input type="text" name="adults_data[0][firstname]" class="form-control"
                                 placeholder="First Name" value="" required />
                              <label for="">First Name</label>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-floating">
                              <input type="text" name="adults_data[0][lastname]" class="form-control"
                                 placeholder="Last Name" value="" required />
                              <label for="">Last Name</label>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <button type="button" class="btn btn-primary align-items-center float-end w-100 h-100 add_adults">
                           Add More
                           </button>
                           <button type="button"
                              class="btn btn-danger mt-2 align-items-center float-end remove-adult-btn remove_adults"
                              style="display:none;">Remove</button>
                        </div>
                     </div>
                  </div>
               </div>
               <hr class="m-0">
               <div class="card-body p-3">
                  <p class="mb-2"><strong>Childs</strong></p>
                  <div class="children-container text-center">
                     <div class="row">
                        <div class="col-md-2">
                           <div class="form-floating">
                              <select name="childs_data[0][age]" class="form-select child_age" required>
                                 <?php for ($x = 1; $x <= 16; $x++) { ?>
                                 <option value="<?=$x?>">
                                    <?=$x?>
                                 </option>
                                 <?php } ?>
                              </select>
                              <label for="">
                              <?=T::age?>
                              </label>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-floating">
                              <input type="text" name="childs_data[0][firstname]" class="form-control"
                                 placeholder="<?=T::first_name?>" value="" required />
                              <label for="">
                              <?=T::first_name?>
                              </label>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="form-floating">
                              <input type="text" name="childs_data[0][lastname]" class="form-control"
                                 placeholder="<?=T::last_name?>" value="" required />
                              <label for="">
                              <?=T::last_name?>
                              </label>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <button type="button" class="btn btn-primary align-items-center float-end w-100 h-100" onclick="addChild()">Add More</button>
                           <button type="button" class="btn btn-danger mt-2 align-items-center float-end remove-child-btn" onclick="removeAdult()" style="display:none;">Remove One</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <script>
               $(document).ready(function () {
                   $(".adults-container").on("click", ".add_adults", function () {
                       // Clone the row
                       const clonedRow = $(".adults_clone:first").clone();

                       // Clear input values in the cloned row
                       clonedRow.find("input").val("");
                       clonedRow.find("select").val("Mr");

                       // Ensure Remove button is visible and Add button is only in the last row
                       clonedRow.find(".add_adults").hide();
                       clonedRow.find(".remove_adults").show();

                       // Append the cloned row to the container
                       $(".adults-container").append(clonedRow);
                   });

                   // Handle remove button click
                   $(".adults-container").on("click", ".remove_adults", function () {
                       $(this).closest(".adults_clone").remove();
                   });
               });

            </script>
            <!-- <script>
               let adultIndex = 1;
               let childIndex = 1;

               function addAdult() {
                   var adultContainer = document.querySelector('.adults-container');

                   var row = adultContainer.querySelector('.row').cloneNode(true);

                   row.querySelector('input[name="adults_data[0][firstname]"]').value = '';
                   row.querySelector('input[name="adults_data[0][lastname]"]').value = '';

                   row.querySelector('select[name="adults_data[0][title]"]').name = `adults_data[${adultIndex}][title]`;
                   row.querySelector('input[name="adults_data[0][firstname]"]').name = `adults_data[${adultIndex}][firstname]`;
                   row.querySelector('input[name="adults_data[0][lastname]"]').name = `adults_data[${adultIndex}][lastname]`;

                   adultContainer.insertBefore(row, adultContainer.querySelector('button'));
                   adultIndex++;

                   if (adultIndex > 1) {
                       adultContainer.querySelector('.remove-adult-btn').style.display = 'block';
                   }
               }

               function removeAdult() {
                   var adultContainer = document.querySelector('.adults-container');
                   var rows = adultContainer.querySelectorAll('.row');

                   if (rows.length > 1) {
                       adultContainer.removeChild(rows[rows.length - 1]);
                       adultIndex--;

                       if (adultIndex === 1) {
                           adultContainer.querySelector('.remove-adult-btn').style.display = 'none';
                       }
                   }
               }

               function addChild() {
                   var childContainer = document.querySelector('.children-container');

                   var row = childContainer.querySelector('.row').cloneNode(true);

                   row.querySelector('input[name="childs_data[0][firstname]"]').value = '';
                   row.querySelector('input[name="childs_data[0][lastname]"]').value = '';

                   row.querySelector('select[name="childs_data[0][age]"]').name = `childs_data[${childIndex}][age]`;
                   row.querySelector('input[name="childs_data[0][firstname]"]').name = `childs_data[${childIndex}][firstname]`;
                   row.querySelector('input[name="childs_data[0][lastname]"]').name = `childs_data[${childIndex}][lastname]`;

                   childContainer.insertBefore(row, childContainer.querySelector('button'));
                   childIndex++;

                   if (childIndex > 1) {
                       childContainer.querySelector('.remove-child-btn').style.display = 'block';
                   }
               }

               function RemoveChild() {
                   var childContainer = document.querySelector('.children-container');
                   var rows = childContainer.querySelectorAll('.row');

                   if (rows.length > 1) {

                       var lastRow = rows[rows.length - 1];
                       childContainer.removeChild(lastRow);

                       childIndex--;

                       // Hide the Remove button if there's only one row left
                       if (childIndex === 1) {
                           childContainer.querySelector('.remove-child-btn').style.display = 'none';
                       }
                   }
               }

               </script> -->
            <div class="d-block"></div>

            <div class="row mb-2">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="" name="price" value="0"
                            placeholder="Amount" required>
                        <label for="">Comission</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="bookingPrice" name="price" readonly
                            placeholder="" required value="USD 330">
                        <label for="bookingPrice">Total Price</label>
                    </div>
                </div>
            </div>

            <div class="row">
               <div class="col-md-2">
                  <div class="form-check d-flex gap-3 align-items-center">
                     <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"  >
                     <label class="form-check-label" for="flexCheckDefault">
                     Send Email
                     </label>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="form-check d-flex gap-3 align-items-center">
                     <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"  >
                     <label class="form-check-label" for="flexCheckDefault">
                     Send SMS
                     </label>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="form-check d-flex gap-3 align-items-center">
                     <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault"  >
                     <label class="form-check-label" for="flexCheckDefault">
                     Send Whatsapp
                     </label>
                  </div>
               </div>
            </div>
            <hr>
            <!-- Submit Button -->
            <div class="text-start">
               <button type="submit" class="btn btn-primary">Submit Booking</button>
            </div>
         </form>
      </div>
   </div>
</div>
  <script>
   $(document).ready(function () {
       const hotelSelect = $('#hotelSelect');
       const roomSelect = $('#roomSelect');
       const roomOption = $('#roomOption');

       $('#locationSelect').on('change', function () {
           const location = $(this).val();
           if (location) {
               hotelSelect.prop('disabled', false);
               $.ajax({
                   url: 'booking-ajax.php',
                   type: 'POST',
                   data: {
                       action: 'get_hotels',
                       location: location
                   },
                   success: function (response) {
                       hotelSelect.html('<option value="" disabled selected>Select a Hotel</option>');
                       if (response.status === 'success') {
                           response.hotels.forEach(function (hotel) {
                               hotelSelect.append(`<option value="${hotel.id}">${hotel.name}</option>`);
                           });
                       } else {
                           console.error('Error fetching hotels:', response.message);
                       }
                   },
                   error: function (xhr, status, error) {
                       console.error('Ajax error:', error);
                   }
               });
           } else {
               hotelSelect.prop('disabled', true).html('<option value="" disabled selected>Select a Hotel</option>');
           }
       });



       hotelSelect.on('change', function () {
           const hotelId = $(this).val();
           if (hotelId) {
               roomSelect.prop('disabled', false);
               $.ajax({
                   url: 'booking-ajax.php',
                   type: 'POST',
                   data: {
                       action: 'get_rooms',
                       hotel_id: hotelId
                   },
                   success: function (response) {
                       roomSelect.html('<option value="" disabled selected>Select a Room</option>');
                       if (response.status === 'success') {
                           response.rooms.forEach(function (room) {
                               roomSelect.append(`<option value="${room.id}">${room.name}</option>`);
                           });
                       } else {
                           console.error('Error fetching rooms:', response.message);
                       }
                   },
                   error: function (xhr, status, error) {
                       console.error('Ajax error:', error);
                   }
               });
           } else {
               roomSelect.prop('disabled', true).html('<option value="" disabled selected>Select a Room</option>');
           }
       });

       roomSelect.on('change', function () {
           const roomId = $(this).val();
           if (roomId) {
               roomOption.prop('disabled', false);
               $.ajax({
                   url: 'booking-ajax.php',
                   type: 'POST',
                   data: {
                       action: 'get_room_options',
                       room_id: roomId
                   },
                   success: function (response) {
                       roomOption.html('<option value="" disabled selected>Select a Room Option</option>');
                       if (response.status === 'success') {
                           response.options.forEach(function (option) {
                               roomOption.append(
                                   `<option value="${option.id}">${response.currency_name} ${option.price} - Adults ${option.adults} Childs ${option.childs}</option>`
                               );
                           });
                       } else {
                           console.error('Error fetching room options:', response.message);
                       }
                   },
                   error: function (xhr, status, error) {
                       console.error('Ajax error:', error);
                   }
               });
           } else {
               roomOption.prop('disabled', true).html('<option value="" disabled selected>Select a Room Option</option>');
           }
       });

//        $('#bookingForm').on('submit', function (e) {
//            e.preventDefault();

//            const formData = $(this).serialize();
//            $.ajax({
//                url: 'booking-ajax.php',
//                type: 'POST',
//                data: formData + '&action=submit_booking',
//                success: function (response) {
//                    if (response.status === 'success') {
//                        showFormAlert('success', response.message); // Show success alert
//                        $('#bookingForm')[0].reset(); // Reset the form
//                        // Clear the dynamically added adult and child rows
//                        $('.adults-container .row:not(:first-child)').remove(); // Remove all added adult rows except the first
//                        $('.children-container .row:not(:first-child)').remove(); // Remove all added child rows except the first

//                        adultIndex = 1; // Reset the adult index for next additions
//                        childIndex = 1;
//                    } else {
//                        showFormAlert('danger', 'Error: ' + response.message); // Show error alert
//                    }
//                },
//                error: function (xhr, status, error) {
//                    console.error('Ajax error:', error);
//                    showFormAlert('danger', 'An unexpected error occurred.'); // Show general error alert
//                }
//            });
//        });

//        function showFormAlert(type, message) {
//            const alertHTML = `
//        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
//            ${message}
//    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">x</button>
//        </div>`;
//            $('#formAlert').html(alertHTML);

//            $('#formAlert .alert').get(0).scrollIntoView({
//                behavior: 'smooth',
//                block: 'center'
//            });

//            setTimeout(() => {
//                $('.alert').alert('close');
//            }, 5000);
//        }


   });
</script>

 <script>
   $(document).ready(function() {
    $('.select2').select2();
   });
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php include "_footer.php" ?>