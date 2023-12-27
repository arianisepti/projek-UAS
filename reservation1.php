<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "uas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pesan"])) {
 
  // Simpan data pemesanan ke session
    $reservation = array(
        'id' => generateReservationId(),
        'nama' => $_POST['nama'],
        'place_type' => $_POST['place_type'], 
        'tanggal_checkin' => $_POST['tanggal_checkin'],
        'tanggal_checkout' => $_POST['tanggal_checkout'],
        'status' => 'PENDING', // Status awal
        'price' => calculatePrice($_POST['place_type'])
    );

    

   // Query untuk menambahkan pemesanan ke dalam tabel
   $stmt = $conn->prepare("INSERT INTO reservations (id, nama, tanggal_checkin, tanggal_checkout, status, place_type, price) VALUES (?, ?, ?, ?, ?, ?, ?)");
   $stmt->bind_param("sssssss", $reservation['id'], $reservation['nama'], $reservation['tanggal_checkin'], $reservation['tanggal_checkout'], $reservation['status'], $reservation['place_type'], $reservation['price']);

   // Execute the statement
   $stmt->execute();

   // Close the statement
   $stmt->close();
  
    // Simpan data pemesanan ke histori
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = array();
    }
    $_SESSION['history'][] = $reservation;

    $message = "Pemesanan berhasil!";
}
function generateReservationId() {
  // Generate a random 6-digit number as the reservation ID
  return mt_rand(100000, 999999);
}

function calculatePrice($placeType)
{
    $placeTypes = [
        'apartment' => 150,
        'villa' => 200,
        'hotel' => 100
    ];

    return $placeTypes[$placeType] ?? 0;
}

if (isset($_GET['cancel'])) {
    $index = $_GET['cancel'];

    if (isset($_SESSION['history'][$index])) {
        // Ubah status pemesanan menjadi "DIBATALKAN"
        $_SESSION['history'][$index]['status'] = 'CANCELLED';
        $message = "Pemesanan berhasil dibatalkan!";
    }
}

if (isset($_GET['delete'])) {
    $index = $_GET['delete'];

    if (isset($_SESSION['history'][$index])) {
        // Hapus pemesanan dari histori
        unset($_SESSION['history'][$index]);
        $message = "Histori pemesanan berhasil dihapus!";
    } else {
        $message = "Pemesanan tidak ditemukan.";
    }
}


$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Foursen-Network Society</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap"
      rel="stylesheet"
    />

    <!-- Icon Font Stylesheet -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet" />
  </head>

  <body>
    <div class="container-xxl bg-white p-0">
      <!-- Spinner Start -->
      <div
        id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"
      >
        <div
          class="spinner-border text-primary"
          style="width: 3rem; height: 3rem"
          role="status"
        >
          <span class="sr-only">Loading...</span>
        </div>
      </div>
      <!-- Spinner End -->

      <!-- Navbar Start -->
      <div class="container-fluid nav-bar bg-transparent">
        <nav class="navbar navbar-expand-lg bg-primary navbar-light py-0 px-4">
          <a
            href="client-index.html"
            class="navbar-brand d-flex align-items-center text-center"
          >
            <div class="icon p-2 me-2">
              <img
                class="img-fluid"
                src="img2/Font Dan Symbol/Untitled-2.png"
                alt="Icon"
                style="width: 30px; height: 30px"
              />
            </div>
            <h1 class="m-0 text-danger">Foursen</h1>
          </a>
          <button
            type="button"
            class="navbar-toggler"
            data-bs-toggle="collapse"
            data-bs-target="#navbarCollapse"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto">
              <a href="client-index.html" class="nav-item nav-link active">Home</a>
              <a href="client-about.html" class="nav-item nav-link">About</a>
              <div class="nav-item dropdown">
                <a
                  href="#"
                  class="nav-link dropdown-toggle"
                  data-bs-toggle="dropdown"
                  >Property</a
                >
                <div class="dropdown-menu rounded-0 m-0">
                  <a href="property-list.html" class="dropdown-item"
                    >Property List</a
                  >
                  <a href="property-type.html" class="dropdown-item"
                    >Property Type</a
                  >
                  <a href="property-agent.html" class="dropdown-item"
                    >Property Agent</a
                  >
                </div>
              </div>
              <div class="nav-item dropdown">
                <a
                  href="#"
                  class="nav-link dropdown-toggle"
                  data-bs-toggle="dropdown"
                  >Pages</a
                >
                <div class="dropdown-menu rounded-0 m-0">
                  <a href="client-testi.html" class="dropdown-item"
                    >Testimonial</a
                  >
                </div>
              </div>
              <a href="client-contact.html" class="nav-item nav-link">Contact</a>
            </div>
            <a href="profil.html" class="btn btn-primary px-3 d-none d-lg-flex"
              >My Profile</a
            >
          </div>
        </nav>
      </div>
      <!-- Navbar End -->

      <!-- Header Start -->
      <div class="container-fluid header bg-white p-0">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
          <div class="col-md-6 p-5 mt-lg-5">
            <h1 class="display-5 animated fadeIn mb-4">
             Reservation Form
            </h1>
            <p class="animated fadeIn mb-4 pb-2">
              Vero elitr justo clita lorem. Ipsum dolor at sed stet sit diam no.
              Kasd rebum ipsum et diam justo clita et kasd rebum sea elitr.
            </p>
            <button class="btn btn-primary py-3 px-5 me-3 animated fadeIn" onclick="scrollToSection('getstarted')"
              >Get Started</button>
          </div>
          <div class="col-md-6 animated fadeIn">
            <div class="owl-carousel header-carousel">
              <div class="owl-carousel-item">
                <img class="img-fluid" src="img/carousel-1.jpg" alt="" />
              </div>
              <div class="owl-carousel-item">
                <img class="img-fluid" src="img/carousel-2.jpg" alt="" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Header End -->

      
      <!-- Category Start -->
      <div class="container-xxl py-5">
        <div class="container">
          <div
            class="text-center mx-auto mb-5 wow fadeInUp"
            data-wow-delay="0.1s"
            style="max-width: 600px"
          >
            <section id="getstarted"><h1 class="mb-3">Property Types</h1></section>
            <p>
              Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut
              dolore lorem kasd vero ipsum sit eirmod sit. Ipsum diam justo sed
              rebum vero dolor duo.
            </p>
          </div>
          <div class="row g-4">
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/PHC-Suite-Room-Bedroom.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Apartement</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/Rental-homes-in-Bali-Home-in-Ubud.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Villa</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/Tampak-Depan-Rumah-Minimalis-Sederhana.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Home</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/meeting1.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Office</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/Morpheus-Hotel-Macau-City-of-Dreams-Lobby-Zaha-Hadid-5abdb4293128340037fb6f66.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Building</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/property-2.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Townhouse</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/shop.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Shop</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
              <a
                class="cat-item d-block bg-light text-center rounded p-3"
                href=""
              >
                <div class="rounded p-4">
                  <div class="icon mb-3">
                    <img
                      class="img-fluid"
                      src="img2/Photo/wp1846068.jpg"
                      alt="Icon"
                    />
                  </div>
                  <h6>Garage</h6>
                  <span>123 Properties</span>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- Category End -->



      <!-- Reservation Start -->
      <div class="container -xxl py-5" style="background-color : #181958">
      <form class="col-md-6 right-box" id="myForm" action="reservation1.php" method="post">
          <div class="row align-items-center">
                <div class="text-white mb-5">
                     <p>RESERVATION</p>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-white fs-6" name="nama" required placeholder="Nama Lengkap">
                </div>
                <div class="input-group mb-3">
                  <select class="form-control form-control-lg bg-white fs-6" name="place_type" required>
                      <option value="apartment">Apartment</option>
                      <option value="villa">Villa</option>
                      <option value="hotel">Hotel</option>
                  </select>
              </div>
              <label class="text-white mb-1">Check-in:
                <div class="input-group mb-3">
                    <input type="date" class="form-control form-control-lg bg-white fs-6"  name="tanggal_checkin" required placeholder="Check-in">
                </div>   
                </label>
                <label class="text-white mb-1">Check-out:
                <div class="input-group mb-3">
                    <input type="date" class="form-control form-control-lg bg-white fs-6"  name="tanggal_checkout" required placeholder="Check-out">
                </div>   
               </label>  
                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-primary w-100 fs-6"type="submit" name="pesan">Booking</button>
                </div>
          </div>
       </form> 
    </div>


    <?php if (isset($_SESSION['history'])) : ?>
        <h2>Reservation History</h2>
        <table>
            <tr>
                <th>RESERVATION ID</th>
                <th>NAME</th>
                <th>TYPE</th>
                <th>CHECK-IN</th>
                <th>CHECK-OUT</th>
                <th>AMOUNT</th>
                <th>STATUS</th>
                <th>CONTINUE/CANCEL</th>
                <th>ACTION</th>
            </tr>
            <?php foreach ($_SESSION['history'] as $index => $reservation) : ?>
                <tr>
                    <td><?php echo $reservation['id']; ?></td>
                    <td><?php echo $reservation['nama']; ?></td>
                    <td><?php echo $reservation['place_type']; ?></td>
                    <td><?php echo $reservation['tanggal_checkin']; ?></td>
                    <td><?php echo $reservation['tanggal_checkout']; ?></td>
                    <td><?php echo $reservation['price']; ?></td>
                    <td><?php echo $reservation['status']; ?></td>
                    <td>
                        <?php if ($reservation['status'] == 'PENDING') : ?>
                            <a href="reservation1.php?cancel=<?php echo $index; ?>">Cancel</a> 
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="reservation1.php?delete=<?php echo $index; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus histori ini?')">Erase</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>Belum ada histori pemesanan</p>
    <?php endif; ?>

      <!-- Reservation End -->



      
      <!-- Call to Action Start -->
      <div class="container-xxl py-5">
        <div class="container">
          <div class="bg-light rounded p-3">
            <div
              class="bg-white rounded p-4"
              style="border: 1px dashed rgba(0, 185, 142, 0.3)"
            >
              <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                  <img
                    class="img-fluid rounded w-100"
                    src="img2/Person/call us.jpg"
                    alt=""
                  />
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                  <div class="mb-4">
                    <h1 class="mb-3">Contact With Our Certified Agent</h1>
                    <p>
                      Eirmod sed ipsum dolor sit rebum magna erat. Tempor lorem
                      kasd vero ipsum sit sit diam justo sed vero dolor duo.
                    </p>
                  </div>
                  <a href="" class="btn btn-primary py-3 px-4 me-2"
                    ><i class="fa fa-phone-alt me-2"></i>Make A Call</a
                  >
                  <a href="" class="btn btn-dark py-3 px-4"
                    ><i class="fa fa-calendar-alt me-2"></i>Get Appoinment</a
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Call to Action End -->

      <!-- Team Start -->
      <div class="container-xxl py-5">
        <div class="container">
          <div
            class="text-center mx-auto mb-5 wow fadeInUp"
            data-wow-delay="0.1s"
            style="max-width: 600px"
          >
            <h1 class="mb-3">Property Agents</h1>
            <p>
              Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut
              dolore lorem kasd vero ipsum sit eirmod sit. Ipsum diam justo sed
              rebum vero dolor duo.
            </p>
          </div>
          <div class="row g-4">
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
              <div class="team-item rounded overflow-hidden">
                <div class="position-relative">
                  <img
                    class="img-fluid"
                    src="img2/Person/person 1.jpg"
                    alt=""
                  />
                  <div
                    class="position-absolute start-50 top-100 translate-middle d-flex align-items-center"
                  >
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-instagram"></i
                    ></a>
                  </div>
                </div>
                <div class="text-center p-4 mt-3">
                  <h5 class="fw-bold mb-0">Full Name</h5>
                  <small>Designation</small>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
              <div class="team-item rounded overflow-hidden">
                <div class="position-relative">
                  <img
                    class="img-fluid"
                    src="img2/Person/Person 2.jpg"
                    alt=""
                  />
                  <div
                    class="position-absolute start-50 top-100 translate-middle d-flex align-items-center"
                  >
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-instagram"></i
                    ></a>
                  </div>
                </div>
                <div class="text-center p-4 mt-3">
                  <h5 class="fw-bold mb-0">Full Name</h5>
                  <small>Designation</small>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
              <div class="team-item rounded overflow-hidden">
                <div class="position-relative">
                  <img
                    class="img-fluid"
                    src="img2/Person/person 3.jpg"
                    alt=""
                  />
                  <div
                    class="position-absolute start-50 top-100 translate-middle d-flex align-items-center"
                  >
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-instagram"></i
                    ></a>
                  </div>
                </div>
                <div class="text-center p-4 mt-3">
                  <h5 class="fw-bold mb-0">Full Name</h5>
                  <small>Designation</small>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
              <div class="team-item rounded overflow-hidden">
                <div class="position-relative">
                  <img class="img-fluid" src="" alt="" />
                  <div
                    class="position-absolute start-50 top-100 translate-middle d-flex align-items-center"
                  >
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a class="btn btn-square mx-1" href=""
                      ><i class="fab fa-instagram"></i
                    ></a>
                  </div>
                </div>
                <div class="text-center p-4 mt-3">
                  <h5 class="fw-bold mb-0">Full Name</h5>
                  <small>Designation</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Team End -->

      <!-- Testimonial Start -->
      <div class="container-xxl py-5">
        <div class="container">
          <div
            class="text-center mx-auto mb-5 wow fadeInUp"
            data-wow-delay="0.1s"
            style="max-width: 600px"
          >
            <h1 class="mb-3">Our Clients Say!</h1>
            <p>
              Eirmod sed ipsum dolor sit rebum labore magna erat. Tempor ut
              dolore lorem kasd vero ipsum sit eirmod sit. Ipsum diam justo sed
              rebum vero dolor duo.
            </p>
          </div>
          <div
            class="owl-carousel testimonial-carousel wow fadeInUp"
            data-wow-delay="0.1s"
          >
            <div class="testimonial-item bg-light rounded p-3">
              <div class="bg-white border rounded p-4">
                <p>
                  Tempor stet labore dolor clita stet diam amet ipsum dolor duo
                  ipsum rebum stet dolor amet diam stet. Est stet ea lorem amet
                  est kasd kasd erat eos
                </p>
                <div class="d-flex align-items-center">
                  <img
                    class="img-fluid flex-shrink-0 rounded"
                    src="img2/Person/person 1.jpg"
                    style="width: 45px; height: 45px"
                  />
                  <div class="ps-3">
                    <h6 class="fw-bold mb-1">Client Name</h6>
                    <small>Profession</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="testimonial-item bg-light rounded p-3">
              <div class="bg-white border rounded p-4">
                <p>
                  Tempor stet labore dolor clita stet diam amet ipsum dolor duo
                  ipsum rebum stet dolor amet diam stet. Est stet ea lorem amet
                  est kasd kasd erat eos
                </p>
                <div class="d-flex align-items-center">
                  <img
                    class="img-fluid flex-shrink-0 rounded"
                    src="img2/Person/Person 2.jpg"
                    style="width: 45px; height: 45px"
                  />
                  <div class="ps-3">
                    <h6 class="fw-bold mb-1">Client Name</h6>
                    <small>Profession</small>
                  </div>
                </div>
              </div>
            </div>
            <div class="testimonial-item bg-light rounded p-3">
              <div class="bg-white border rounded p-4">
                <p>
                  Tempor stet labore dolor clita stet diam amet ipsum dolor duo
                  ipsum rebum stet dolor amet diam stet. Est stet ea lorem amet
                  est kasd kasd erat eos
                </p>
                <div class="d-flex align-items-center">
                  <img
                    class="img-fluid flex-shrink-0 rounded"
                    src="img2/Person/person 3.jpg"
                    style="width: 45px; height: 45px"
                  />
                  <div class="ps-3">
                    <h6 class="fw-bold mb-1">Client Name</h6>
                    <small>Profession</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Testimonial End -->

      <!-- Footer Start -->
      <div
        class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn"
        data-wow-delay="0.1s"
      >
        <div class="container py-5">
          <div class="row g-5">
            <div class="col-lg-3 col-md-6">
              <h5 class="text-white mb-4">Get In Touch</h5>
              <p class="mb-2">
                <i class="fa fa-map-marker-alt me-3"></i>Ahmad Yani Street, Jakarta, Indonesia
              </p>
              <p class="mb-2">
                <i class="fa fa-phone-alt me-3"></i>+012 345 67890
              </p>
              <p class="mb-2">
                <i class="fa fa-envelope me-3"></i>info@example.com
              </p>
              <div class="d-flex pt-2">
                <a class="btn btn-outline-light btn-social" href=""
                  ><i class="fab fa-twitter"></i
                ></a>
                <a class="btn btn-outline-light btn-social" href=""
                  ><i class="fab fa-facebook-f"></i
                ></a>
                <a class="btn btn-outline-light btn-social" href=""
                  ><i class="fab fa-youtube"></i
                ></a>
                <a class="btn btn-outline-light btn-social" href=""
                  ><i class="fab fa-linkedin-in"></i
                ></a>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <h5 class="text-white mb-4">Quick Links</h5>
              <a class="btn btn-link text-white-50" href="">About Us</a>
              <a class="btn btn-link text-white-50" href="">Contact Us</a>
              <a class="btn btn-link text-white-50" href="">Our Services</a>
              <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
              <a class="btn btn-link text-white-50" href=""
                >Terms & Condition</a
              >
            </div>
            <div class="col-lg-3 col-md-6">
              <h5 class="text-white mb-4">Photo Gallery</h5>
              <div class="row g-2 pt-2">
                <div class="col-4">
                  <img
                    class="img-fluid rounded bg-light p-1"
                    src="img/property-1.jpg"
                    alt=""
                  />
                </div>
                <div class="col-4">
                  <img
                    class="img-fluid rounded bg-light p-1"
                    src="img/property-2.jpg"
                    alt=""
                  />
                </div>
                <div class="col-4">
                  <img
                    class="img-fluid rounded bg-light p-1"
                    src="img/property-3.jpg"
                    alt=""
                  />
                </div>
                <div class="col-4">
                  <img
                    class="img-fluid rounded bg-light p-1"
                    src="img/property-4.jpg"
                    alt=""
                  />
                </div>
                <div class="col-4">
                  <img
                    class="img-fluid rounded bg-light p-1"
                    src="img/property-5.jpg"
                    alt=""
                  />
                </div>
                <div class="col-4">
                  <img
                    class="img-fluid rounded bg-light p-1"
                    src="img/property-6.jpg"
                    alt=""
                  />
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6">
              <h5 class="text-white mb-4">Newsletter</h5>
              <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
              <div class="position-relative mx-auto" style="max-width: 400px">
                <a class="btn btn-primary py-3 px-5 mt-3" href="logout.php">Logout</a>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="copyright">
            <div class="row">
              <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; <a class="border-bottom" href="#">Your Site Name</a>, All
                Right Reserved.

                <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                Designed By
                <a class="border-bottom" href="https://htmlcodex.com"
                  >HTML Codex</a
                >
              </div>
              <div class="col-md-6 text-center text-md-end">
                <div class="footer-menu">
                  <a href="">Home</a>
                  <a href="">Cookies</a>
                  <a href="">Help</a>
                  <a href="">FQAs</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer End -->

      <!-- Back to Top -->
      <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"
        ><i class="bi bi-arrow-up"></i
      ></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <script>
      function scrollToSection(getstarted) {
        const section = document.getElementById(getstarted);
        if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
        }
      }
    </script>

  
  </body>
</html>
