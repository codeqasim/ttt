<?php
require_once '_config.php';
auth_check();
$title = T::booking .' '. T::edit;


?>
<div class="page_head">
    <div class="panel-heading">
        <div class="float-start">
            <p class="m-0 page_title">
                <?=T::edit.' '. T::booking?>
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

<div class="mt-1">
    <div class="p-3">
        <?=T::booking.' '.T::id?>
        <strong>
            <?php 
        if (isset($_GET['booking'])){ echo $_GET['booking']; }?>
        </strong>
        <hr>

        <?php
        if (!empty($_GET['booking_id']) && !empty($_GET['module']) && !empty($_GET['booking_status']) && !empty($_GET['payment_status']) && !empty($_GET['checkin']) && !empty($_GET['checkout'])) {
            $hotel_id = $_GET['hotel_id'];
            $hotel_data = $db->select('hotels', ['name'], ['id' => $hotel_id]);
            $hotel_name = $hotel_data[0]['name'] ?? '';

            $table_name = $_GET['module'] . "_bookings";
            $existing_data = $db->select($table_name, "*", ['booking_ref_no' => $_GET['booking_id']]);
            $existing_user_data = json_decode($existing_data[0]['user_data'] ?? '{}', true);

            $updated_user_data = array_merge($existing_user_data, [
                'first_name' => $_GET['first_name'] ?? $existing_user_data['first_name'] ?? null,
                'last_name' => $_GET['last_name'] ?? $existing_user_data['last_name'] ?? null,
                'email' => $_GET['email'] ?? $existing_user_data['email'] ?? null,
                'phone' => $_GET['phone'] ?? $existing_user_data['phone'] ?? null,
            ]);

            $user_data_json = json_encode($updated_user_data);

            if (isset($_GET['room_select'])) {
                $room_id = $_GET['room_select'];

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

                if (!empty($room_details)) {
                    $room_data = [
                        "room_id" => $room_details[0]['id'],
                        "room_name" => $room_details[0]['name'],
                        "room_price" => $_POST['room_price'] ?? '0.00',
                        "room_quantity" => !empty($room_options) ? $room_options[0]['quantity'] : "1",
                        "room_extrabed_price" => $room_details[0]['extra_bed_charges'],
                        "room_extrabed" => $room_details[0]['extra_bed'],
                        "room_actual_price" => !empty($room_options) ? $room_options[0]['price'] : "0.00"
                    ];

                    $room_data_json = json_encode([$room_data]);
                }
            }

            $hotel_img = $db->select("hotels_images", "*", ["hotel_id" => $_GET['hotel_id']]);

            $db->update(
                $table_name,
                [
                    'booking_date' => $_GET['booking_date'],
                    'booking_status' => $_GET['booking_status'],
                    'payment_status' => $_GET['payment_status'],
                    'checkin' => $_GET['checkin'],
                    'checkout' => $_GET['checkout'],
                    'hotel_id' => $hotel_id,
                    'hotel_name' => $hotel_name,
                    'hotel_img' => $hotel_img[0]["img"],
                    'first_name' => $_GET['first_name'],
                    'last_name' => $_GET['last_name'],
                    'email' => $_GET['email'],
                    'agent_id' => $_GET['agent_id'],
                    'booking_note' => $_GET['bookingnote'],
                    'cancellation_terms' => $_GET['cancellation_terms'],
                    'phone' => $_GET['phone'],
                    'user_data' => $user_data_json,
                    'price_original' => $_GET['room_price'],
                    'platform_comission' => $_GET['platform_comission'],
                    'tax' => $_GET['tax'],
                    'agent_fee' => $_GET['agent_comission'],
                    'price_markup' => $_GET['bookingPrice'],
                    'supplier_payment_status' => $_GET['supplier_payment_status'],
                    'due_date' => $_GET['due_date'],
                    'room_data' => $room_data_json
                ],
                ['booking_ref_no' => $_GET['booking_id']]
            );

            REDIRECT('./bookings.php');
        }

        if (!empty($_GET['booking']) && !empty($_GET['module'])) {
            $table_name = $_GET['module'] . "_bookings";
            $parm = [
                'booking_ref_no' => $_GET['booking'] ?? '',
            ];
            $data = $db->select($table_name, "*", $parm);

            $user_data = json_decode($data[0]['user_data'] ?? '{}', true);

            $email = $user_data['email'] ?? '';
            $first_name = $user_data['first_name'] ?? '';
            $last_name = $user_data['last_name'] ?? '';
            $phone = $user_data['phone'] ?? '';

            $room_data = json_decode($data[0]['room_data'] ?? '{}', true);

            if (!empty($room_data)) {
                $room_id = $room_data[0]['room_id'];
                $room_name = $room_data[0]['room_name'];
            }
        } else {
            REDIRECT('./bookings.php');
        }
        ?>


        <form class="row g-3" id="search">
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="booking_id" name="booking_id"
                        value="<?= $data[0]['booking_ref_no'] ?? '' ?>" readonly>
                    <label for="">Booking ID</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="date" class="form-control" id="booking_date" name="booking_date"
                        value="<?= $data[0]['booking_date'] ?? '' ?>">
                    <label for="">Booking Date</label>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-floating">
                    <select class="form-select booking_status" id="search_type" name="booking_status">
                        <option value="">Select Type</option>
                        <option value="pending" <?=($data[0]['booking_status'] ?? '' )==="pending" ? "selected" : "" ;?>
                            >
                            <?=T::pending?>
                        </option>
                        <option value="confirmed" <?=($data[0]['booking_status'] ?? '' )==="confirmed" ? "selected" : ""
                            ;?>>
                            <?=T::confirmed?>
                        </option>
                        <option value="cancelled" <?=($data[0]['booking_status'] ?? '' )==="cancelled" ? "selected" : ""
                            ;?>>
                            <?=T::cancelled?>
                        </option>
                    </select>
                    <label for="">
                        <?=T::booking?>
                        <?=T::status?>
                    </label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <select id="search_type" name="payment_status" class="form-select payment_status">
                        <option value="">Select Type</option>
                        <option value="paid" <?=($data[0]['payment_status'] ?? '' )==="paid" ? "selected" : "" ;?>>
                            <?=T::paid?>
                        </option>
                        <option value="unpaid" <?=($data[0]['payment_status'] ?? '' )==="unpaid" ? "selected" : "" ;?>>
                            <?=T::unpaid?>
                        </option>
                        <option value="refunded" <?=($data[0]['payment_status'] ?? '' )==="refunded" ? "selected" : ""
                            ;?>>
                            <?=T::refunded?>
                        </option>
                    </select>
                    <label for="">
                        <?=T::payment?>
                        <?=T::status?>
                    </label>
                </div>
            </div>
            <?php
                $agents = $db->select('users', '*', [
                    'user_type' => 'agent',  
                    'status' => 1
                ]);

                $selectedAgentId = $data[0]['agent_id'] ?? null; 
            ?>

            <div class="col-md-3">
                <div class="form-floating">
                <select id="supplier_payment_status" name="supplier_payment_status" class="form-select" required>
                        <option value="" disabled selected>Supplier Payment Status</option>
                        <option value="paid" <?=($data[0]['supplier_payment_status'] ?? '' )==="paid" ? "selected" : "";?>>
                            <?=T::paid?>
                        </option>
                        <option value="unpaid" <?=($data[0]['supplier_payment_status'] ?? '' )==="unpaid" ? "selected" : "";?>>
                            <?=T::unpaid?>
                        </option>
                        <!-- <option value="refunded" <?=($data[0]['payment_status'] ?? '' )==="refunded" ? "selected" : ""
                            ;?>>
                            <?=T::refunded?>
                        </option> -->
                    </select>
                    <!-- <select class="form-select" id="agent_id" name="agent_id">
                        <option value="">Select Agent</option>
                        <?php foreach ($agents as $agent): ?>
                        <option value="<?= $agent['user_id'] ?>" <?=($selectedAgentId==$agent['user_id']) ? "selected"
                            : "" ; ?>>
                            <?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select> -->
                    <label for="agent_select">Supplier Payment Status</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                <input type="date" class="form-control" id="due_date" name="due_date" autocomplete="off" value="<?=($data[0]['due_date'])?>" required>
                     <label for="due_date">Due Date</label>
            </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <select class="form-select" id="agent_id" name="agent_id">
                        <option value="">Select Agent</option>
                        <?php foreach ($agents as $agent): ?>
                        <option value="<?= $agent['user_id'] ?>" <?=($selectedAgentId==$agent['user_id']) ? "selected"
                            : "" ; ?>>
                            <?= htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="agent_select">Agent</label>
                </div>
            </div>

            <?php $hotels = $db->select('hotels', '*', ['status' => 1]); ?>
            <div class="col-md-6">
                <div class="form-floating">
                    <select class="form-select" id="hotel_select" name="hotel_id">
                        <option value="">Select Hotel</option>
                        <?php foreach ($hotels as $hotel): ?>
                        <option value="<?= $hotel['id'] ?>" <?=($data[0]['hotel_id'] ?? '' )==$hotel['id'] ? "selected"
                            : "" ; ?>>
                            <?= $hotel['name'] ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="hotel_select">Hotel</label>
                </div>
            </div>

            <?php

            // $room_id = $existing_room_data['room_id'] ?? '';
            // $room_name = $existing_room_data['room_name'] ?? ''; 

            ?>

            <div class="col-md-6">
                <div class="form-floating">
                    <select class="form-select" id="room_select" name="room_id" required>
                        <option value="">Select Room</option>
                        <?php if (!empty($room_id)): ?>
                        <option value="<?= $room_id ?>" selected>
                            <?= ($room_name ?? ''); ?>
                        </option>
                        <?php endif; ?>
                    </select>
                    <label for="room_select">Room</label>
                </div>
            </div>

            <!-- Add First Name and Last Name Fields -->

            <div class="col-md-12">
                <div class="card" style="margin-block-end:0px;">
                    <div class="card-header bg-primary text-black">
                        <strong>Travellers</strong>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="<?= $first_name ?>">
                                    <label for="first_name">First Name</label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?= $last_name ?>">
                                    <label for="last_name">Last Name</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?= $email ?>">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="phone" name="phone"
                                        value="<?= $phone ?>">
                                    <label for="phone">Phone</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card" style="margin-block-end:0px;">
                    <div class="card-header bg-primary text-black">
                        <strong>Booking Note </strong>
                    </div>
                    <div class="card-body p-3">
                        <textarea name="bookingnote" class="form-control" id="bookingnote"
                            rows="4"><?= $data[0]['booking_note'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card" style="margin-block-end:0px;">
                    <div class="card-header bg-primary text-black">
                        <strong>Cancellation terms & policy</strong>
                    </div>
                    <div class="card-body p-3">
                        <textarea name="cancellation_terms" class="form-control" id="cancellation_terms"
                            rows="4"><?= $data[0]['cancellation_terms'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <?php 
            $curreny = $db->select("currencies", "*", ["default" => 1,]);?>
                <small for="">Room Price</small>
                <div class="form-floating">
                    <div class="input-group">
                        <input type="number" class="form-control rounded-0" id="room_price" name="room_price"
                            value="<?= $data[0]['price_original'] ?? '' ?>" required>
                        <span class="input-group-text text-white bg-primary">
                            <?= $curreny[0]['name']?>
                        </span>
                    </div>
                    <!-- <label for="">Room Price</label> -->
                </div>
            </div>

            <div class="col-md-2">
                <small for="">Platform Commission</small>
                <div class="form-floating">
                    <div class="input-group">
                        <input type="number" class="form-control rounded-0" id="platform_comission"
                            name="platform_comission" value="<?= $data[0]['platform_comission'] ?? '' ?>" required>
                        <span class="input-group-text text-white bg-primary">
                            <?= $curreny[0]['name']?>
                        </span>
                    </div>
                    <!-- <label for="">Platform Commission</label> -->
                </div>
            </div>

            <div class="col-md-2">
                <small for="">Tax</small>
                <div class="form-floating">
                    <div class="input-group">
                        <input type="number" class="form-control rounded-0" id="tax" name="tax"
                            value="<?= $data[0]['tax'] ?? '' ?>" required>
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
                        <input type="number" class="form-control rounded-0" id="agent_comission" name="agent_comission"
                            value="<?= $data[0]['agent_fee'] ?? '' ?>" required>
                        <span class="input-group-text text-white bg-primary">%</span>
                    </div>
                    <!-- <label for="">Agent Commission</label> -->
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-floating">
                    <small for="">Total Price</small>
                    <div class="input-group">
                        <input type="text" class="form-control fw-semibold text-dark" id="bookingPrice"
                            value="<?= $data[0]['price_markup'] ?? '' ?>" name="price">
                    </div>
                </div>
            </div>
            <!-- Check-in Date -->
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="checkin form-control" id="checkin" name="checkin" autocomplete="off"
                        value="<?= $data[0]['checkin'] ?? '' ?>">
                    <label for="checkin">Check-in Date</label>
                </div>
            </div>

            <!-- Check-out Date -->
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="text" class="checkout form-control" id="checkout" name="checkout" autocomplete="off"
                        value="<?= $data[0]['checkout'] ?? '' ?>">
                    <label for="checkout">Check-out Date</label>
                </div>
            </div>
            <input type="hidden" id="booking_id" name="booking_id" value="<?=$_GET['booking']?>">
            <input type="hidden" id="module" name="module" value="<?=$_GET['module']?>">
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 h-100 rounded-4"
                    style="border-radius: 8px !important;">
                    <?=T::submit?>
                </button>
            </div>
        </form>
        <script>
$(document).ready(function () {
    function updateRooms(hotelId, selectedRoomId) {
        if (hotelId) {
            $.ajax({
                url: 'booking-ajax.php',
                method: 'POST',
                data: {
                    action: 'get_rooms_update',  
                    hotel_id: hotelId
                },
                dataType: 'json',
                success: function (response) {
                    $('#room_select').html('<option value="">Select Room</option>');

                    if (response.length > 0) {
                        response.forEach(function (room) {
                            var selected = room.id == selectedRoomId ? 'selected' : '';
                            $('#room_select').append('<option value="' + room.id + '" ' + selected + '>' + room.name + '</option>');
                        });
                    } else {
                        $('#room_select').append('<option value="">No rooms available</option>');
                    }
                },
                error: function () {
                    alert('Error fetching rooms.');
                }
            });
        } else {
            $('#room_select').html('<option value="">Select Room</option>');
        }
    }

    var selectedHotelId = $('#hotel_select').val();
    var selectedRoomId = $('#room_select').val();

    updateRooms(selectedHotelId, selectedRoomId);

    $('#hotel_select').change(function () {
        var hotelId = $(this).val();
        updateRooms(hotelId, selectedRoomId);
    });
});


        </script>
        <script>
            $("#search").submit(function (event) {
                event.preventDefault();

                var booking_id = $("#booking_id").val();
                var module = $("#module").val();
                var booking_date = $("#booking_date").val();
                var booking_status = $(".booking_status").val();
                var payment_status = $(".payment_status").val();
                var checkin = $("#checkin").val();
                var checkout = $("#checkout").val();
                var hotel_id = $("#hotel_select").val();
                var first_name = $("#first_name").val();  // Get first_name
                var last_name = $("#last_name").val();    // Get last_name
                var email = $("#email").val();
                var phone = $("#phone").val();
                var bookingnote = $("#bookingnote").val();
                var agent_id = $("#agent_id").val();

                var room_price = $("#room_price").val();
                var platform_comission = $("#platform_comission").val();
                var tax = $("#tax").val();
                var agent_comission = $("#agent_comission").val();
                var bookingPrice = $("#bookingPrice").val();
                var room_select = $("#room_select").val();cancellation_terms
                var cancellation_terms = $("#cancellation_terms").val();
                var supplier_payment_status = $("#supplier_payment_status").val();
                var due_date = $("#due_date").val();

                // Send the updated data back to the server via query parameters or AJAX
                window.location.href = "<?=$root?>/admin/booking_update.php?booking_id=" + booking_id + "&module=" + module + "&booking_date=" + booking_date + "&booking_status=" + booking_status + "&payment_status=" + payment_status + "&checkin=" + checkin + "&checkout=" + checkout + "&hotel_id=" + hotel_id + "&first_name=" + first_name + "&last_name=" + last_name + "&email=" + email + "&phone=" + phone + "&room_price=" + room_price + "&platform_comission=" + platform_comission + "&tax=" + tax + "&agent_comission=" + agent_comission + "&bookingPrice=" + bookingPrice + "&bookingnote=" + bookingnote + "&agent_id=" + agent_id + "&room_select=" + room_select + "&supplier_payment_status=" + supplier_payment_status + "&due_date=" + due_date + "&cancellation_terms=" + cancellation_terms;

            });
        </script>
        <script>
            //function booking_status(data)
            //{
            //    var booking_id = $("#booking_id").val();
            //    var module = $("#module").val();
            //    alert(data.value);
            //    window.location.href = "<?//=$root?>//booking_update.php?booking="+booking_id+"&module="+module+"&booking_status="+data.value;
            //}

        </script>
    </div>
    <?php include "_footer.php" ?>