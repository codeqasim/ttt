<?php
   require_once '_config.php';
   auth_check();

   $title = T::add .' '. T::booking;
   include "_header.php";

   if ($_SERVER['REQUEST_METHOD'] === 'POST'){
       // Collect main booking parameters
       $params = [
           "booking_ref_no" => date('Ymdhis') . rand(),
           "location" => $_POST['location'],
           "hotel_id" => $_POST['hotel'],
           "price_markup" => $_POST['price'],
           "first_name" => $_POST['first_name'],
           "last_name" => $_POST['last_name'],
           "email" => $_POST['email'],
           "supplier" => "hotels",
           "checkin" => $_POST['checkin'],
           "checkout" => $_POST['checkout'],
           "agent_id" => $_POST['agent'],
         //   "comission" => $_POST['comission'],
           "booking_date" => date('Y-m-d'),
           
           "agent_fee" => $_POST['agent_comission'],
           "tax" => $_POST['tax'],
           "platform_comission" => $_POST['platform_comission'],
           "price_original" => $_POST['room_price'],
           "booking_note" => $_POST['bookingnote'],
       ];

       // Collect user data
       $user_data = [
           "first_name" => $_POST['first_name'],
           "last_name" => $_POST['last_name'],
           "email" => $_POST['email'],
           "phone" => $_POST['phone'],
           "address" => $_POST['address'],
           "nationality" => $_POST['nationality'],
           "country_code" => $_POST['country_code'],
           "user_id" => $_POST['user_id']
       ];
       $params['user_data'] = json_encode($user_data);

       // Collect the travelers' data in the required format
       $travelers_data = [];
       // Process adults data
       if (isset($_POST['adults_data'])) {
           foreach ($_POST['adults_data'] as $adult) {
               $travelers_data[] = [
                   "traveller_type" => "adults",
                   "title" => $adult['title'],
                   "first_name" => $adult['firstname'],
                   "last_name" => $adult['lastname'],
                   "age" => ""
               ];
           }
       }

       // Encode travelers' data into JSON
       $params['guest'] = json_encode($travelers_data);

       // Fetch hotel name
       $hotel_id = $_POST['hotel'];
       $hotel_data = $db->select("hotels", ["name"], ["id" => $hotel_id]);
       if (!empty($hotel_data)) {
           $params['hotel_name'] = $hotel_data[0]['name'];
       }

       // Fetch currency
       $currency = $db->select("currencies", ["name"], ["default" => 1]);
       if (!empty($currency)) {
           $params['currency_markup'] = $currency[0]['name'];
       }


       if (isset($_POST['room'])) {
        $room_id = $_POST['room'];

        $room_details = $db->select("hotels_rooms", [
            "[>]hotels_settings" => ["room_type_id" => "id"]
        ], [
            "hotels_rooms.id",
            "hotels_settings.name",
            "hotels_rooms.extra_bed_charges",
            "hotels_rooms.extra_bed",
        ], [
            "hotels_rooms.id" => $room_id,
            "hotels_rooms.status" => 1
        ]);

        $room_options = $db->select("hotels_rooms_options", ["price", "quantity"], [
            "room_id" => $room_id
        ]);

      //   if (!empty($room_options)) {
      //       $params['price_original'] = $room_options[0]['price'];
      //   }

        if (!empty($room_details)) {
            $room_data = [
                "room_id" => $room_details[0]['id'],
                "room_name" => $room_details[0]['name'],
                "room_price" => $_POST['room_price'],
                "room_quantity" => !empty($room_options) ? $room_options[0]['quantity'] : "1",
                "room_extrabed_price" => $room_details[0]['extra_bed_charges'],
                "room_extrabed" => $room_details[0]['extra_bed'],
                "room_actual_price" => !empty($room_options) ? $room_options[0]['price'] : "0.00"
            ];

            $params['room_data'] = json_encode([$room_data]);
        }
    }

       // Insert booking into the database
       $db->insert("hotels_bookings", [
           $params,
       ]);

       // Get the inserted booking ID (if needed)
       $id = $db->id();
       if (isset($id)) {
           $_SESSION['booking_inserted'] = true;
       }
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
</div>
<div class="mt-1">
   <div class="p-3">
      <div class="container px-5">
      <?php
         if (isset($_SESSION['booking_inserted'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <strong>Success!</strong> Your booking has been added to the system.
            </div>
         <?php
         endif;
         unset($_SESSION['booking_inserted']);
      ?>

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
            </div>
            <!-- Check-in and Check-out Dates -->
            <div class="row g-3 mb-3">
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="checkin form-control" id="" name="checkin" autocomplete="off" required
                        value="<?php $d=strtotime(" +3 Days"); echo date("d-m-Y", $d); ?>">
                     <label for="checkinDate">Check-in Date</label>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="checkout form-control" id="" name="checkout" required autocomplete="off"
                        value="<?php $d=strtotime(" +4 Days"); echo date("d-m-Y", $d); ?>">
                     <label for="checkoutDate">Check-out Date</label>
                  </div>
               </div>
               <div class="col-md-6">
               <div class="form-floating">
                   <select class="select2" id="agentSelect" name="agent" required>
                     <option value="" selected>Select an Agent</option>
                        <?php
                           // Fetch agents from users table where user_type is 'agent'
                           $agents = $db->select("users", "*", ["user_type" => "agent"]);
                           foreach ($agents as $agent) {
                           ?>
                     <option value="<?= $agent['user_id']?>">
                           <?= $agent['first_name'] . ' ' . $agent['last_name'] ?>
                     </option>
                        <?php } ?>
                  </select>
                  <!-- <label for="agentSelect">Select an Agent</label> -->
               </div>
               </div>

            </div>
            <!-- Number of Travelers -->

            <!-- Client Details -->
            <div class="row mb-3 g-3">
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="form-control" id="firstName" name="first_name"
                        placeholder="Enter first name" required>
                     <label for="firstName">First Name</label>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="form-floating">
                     <input type="text" class="form-control" id="lastName" name="last_name"
                        placeholder="Enter last name" required>
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
                           <button type="button"
                              class="btn btn-primary align-items-center float-end w-100 h-100 add_adults">
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
            </div>
            <script>
               $(document).ready(function () {
                  let adultIndex = 1; // Start index for adults

                  $(".adults-container").on("click", ".add_adults", function () {
                     const clonedRow = $(".adults_clone:first").clone();

                     // Clear input values
                     clonedRow.find("input").val("");
                     clonedRow.find("select").val("Mr");

                     // Update the `name` attributes with unique index
                     clonedRow.find("[name^='adults_data']").each(function () {
                        const nameAttr = $(this).attr("name");
                        $(this).attr("name", nameAttr.replace(/\[0\]/, `[${adultIndex}]`));
                     });

                     clonedRow.find(".add_adults").hide();
                     clonedRow.find(".remove_adults").show();

                     $(".adults-container").append(clonedRow);
                     adultIndex++;
                  });

                  $(".adults-container").on("click", ".remove_adults", function () {
                     $(this).closest(".adults_clone").remove();
                  });

               });
            </script>
                        <div class="card mb-2">
               <div class="card-header bg-primary text-dark">
                  <strong class="">
                  booking note
               </div>
               <div class="card-body p-3">
                  <textarea name="bookingnote" class="form-control" id="bookingnote" rows="4" placeholder="Add booking note here..."></textarea>
               </div>
               <hr class="m-0">
            </div>
            <?php 
            $curreny = $db->select("currencies", "*", ["default" => 1,]);?>
            <div class="d-block"></div>
            <div class="row mb-3 g-3">
            <div class="col-md-2">
               <small for="">Room Price</small>
               <div class="form-floating">
                  <div class="input-group">
                     <input type="number" class="form-control rounded-0" id="" name="room_price" value="0" required>
                     <span class="input-group-text text-white bg-primary"><?= $curreny[0]['name']?></span>
                  </div>
                  <!-- <label for="">Room Price</label> -->
               </div>
            </div>

            <div class="col-md-2">
               <small for="">Platform Commission</small>
               <div class="form-floating">
                  <div class="input-group">
                     <input type="number" class="form-control rounded-0" id="" name="platform_comission" value="0" required>
                     <span class="input-group-text text-white bg-primary"><?= $curreny[0]['name']?></span>
                  </div>
                  <!-- <label for="">Platform Commission</label> -->
               </div>
            </div>

            <div class="col-md-2">
               <small for="">Tax</small>
               <div class="form-floating">
                  <div class="input-group">
                     <input type="number" class="form-control rounded-0" id="" name="tax" value="14" required>
                     <span class="input-group-text text-white bg-primary">%</span>
                  </div>
                  <!-- <label for="">Tax</label> -->
               </div>
            </div>

            <!-- Agent Commission -->
            <div class="col-md-2">
               <div class="form-floating">
                  <small for="">Agent Commission</small>
                  <div class="input-group">
                     <input type="number" class="form-control rounded-0" id="" name="agent_comission" value="0" required>
                     <span class="input-group-text text-white bg-primary">%</span>
                  </div>
                  <!-- <label for="">Agent Commission</label> -->
               </div>
            </div>

            <div class="col-md-4">
               <div class="form-floating">
                  <small for="">Total Price</small>
                  <div class="input-group">
                  <input type="text" class="form-control fw-semibold text-dark" id="bookingPrice" name="price" readonly>
                  </div>
               </div>
            </div>
            </div>
            <div class="row">
               <div class="col-md-2">
                  <div class="form-check d-flex gap-3 align-items-center">
                     <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                     <label class="form-check-label" for="flexCheckDefault">
                        Send Email
                     </label>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="form-check d-flex gap-3 align-items-center">
                     <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                     <label class="form-check-label" for="flexCheckDefault">
                        Send SMS
                     </label>
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="form-check d-flex gap-3 align-items-center">
                     <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
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

      // Function to calculate the total price
      function calculateTotalPrice() {
         // Get values from input fields
         const roomPrice = parseFloat($('input[name="room_price"]').val()) || 0;
         const platformCommission = parseFloat($('input[name="platform_comission"]').val()) || 0;
         const agentCommissionPercent = parseFloat($('input[name="agent_comission"]').val()) || 0;
         const taxPercent = parseFloat($('input[name="tax"]').val()) || 0; 

         // Calculate the agent commission based on room price and platform commission
         const agentCommission = (roomPrice + platformCommission) * (agentCommissionPercent / 100);

         // Calculate total before tax (room price + platform commission + agent commission)
         const totalBeforeTax = roomPrice + platformCommission + agentCommission;

         // Calculate tax amount
         const taxAmount = totalBeforeTax * (taxPercent / 100);

         // Calculate total price (including tax)
         const totalPrice = totalBeforeTax + taxAmount;

         // Update the total price field
         $('#bookingPrice').val(totalPrice.toFixed(2));
      }

      // Bind change events to fields to recalculate the total price
      $('input[name="room_price"], input[name="platform_comission"], input[name="agent_comission"], input[name="tax"]').on('input', function () {
         calculateTotalPrice();
      });

      // Initial calculation when the page loads
      calculateTotalPrice();

      // Handle location selection change
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

      // Handle hotel selection change
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

      // Handle agent selection change
      $('#agentSelect').on('change', function () {
         const agentId = $(this).val();
         if (agentId) {
            $.ajax({
               url: 'booking-ajax.php',
               type: 'POST',
               data: {
                  action: 'get_agent_markup',
                  agent_id: agentId
               },
               success: function (response) {
                  if (response.status === 'success') {
                     $('input[name="agent_comission"]').val(response.markup);
                     calculateTotalPrice(); // Recalculate total price with new commission
                  } else {
                     console.error('Error fetching agent commission:', response.message);
                  }
               },
               error: function (xhr, status, error) {
                  console.error('Ajax error:', error);
               }
            });
         } else {
            $('input[name="agent_comission"]').val('0'); // Reset agent commission if no agent is selected
            calculateTotalPrice(); // Recalculate total price
         }
      });
   });
</script>

<script>
   $(document).ready(function () {
      $('.select2').select2();
   });
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php include "_footer.php" ?>