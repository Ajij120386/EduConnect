


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Me | EduConnect</title>
  <style>
    :root {
      --primary: #ff3d00;
      --bg-dark: #07051a;
      --white: #fff;
      --text-light: #ccc;
      --accent: #00e5ff;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: var(--bg-dark);
      color: var(--text-light);
      line-height: 1.6;
    }

 a,a:hover
 {
     text-decoration: none;
 }

 .btn-2
 {
     background-color: var(--white);
     padding: 15px 40px;
     border: none;
     border-radius: 30px;
     color: black;
     font-size: 16px;
     text-transform: capitalize;
     box-shadow: var(--shadow-black-100);
     font-weight: 500;
     transition: all 0.5s ease;
 }
 .btn-2:hover
 {
     color: var(--white);
     background-color: red;
 }

    .section-padding
 {
     padding: 80px 0;
 }
 .section-title
 {
     margin-bottom: 60px;
     font-family: "Kaushan Script", cursive;
 }
 .section-title h4
 {
     font-size: 20px;
     font-weight: 700;
     color: white;
     text-transform: capitalize;
     text-align: center;
 }
 .section-title h2
 {
     font-size: 30px;
     color: var(--black-900);
     font-weight: 700;
     text-transform: uppercase;
     margin: 0;
     text-align: center;
 }
 .section-title h2 span 
 {
     color: var(--main-color);
 }
 .contact 
{
    background-color: var(--shadow-black-100);
    font-family: "Kaushan Script", cursive;
    height: 600px;
}

.contact .row{

    display: flex;
    justify-content: space-around;
    

}
.contact-form {

    display: flex;
    justify-content: space-around;
    align-items: center;

}
.contact-info h3
{
    font-size: 22px;
    color: var(--black-900);
    font-weight: 500;
    margin: 0 0 40px;
}
.contact-info-item
{
    position: relative;
    padding-left: 55px;
    margin-bottom: 30px;
    font-family: "Kaushan Script", cursive;
}
.contact-info-item i
{
    position: absolute;
    height: 40px;
    width: 40px;
    left: 0;
    top: 0;
    border-radius: 50%;
    font-size: 16px;
    color: var(--main-color);
    border: 1px solid var(--main-color);
    text-align: center;
    line-height: 38px;
    
}
.contact-info-item h4
{
    font-size: 18px;
    font-weight: 400;
    margin: 0 0 10px;
    color: var(--black-900);
}
.contact-info-item p
{
    font-size: 16px;
    font-weight: 300;
    margin: 0;
    line-height: 26px;
    color: var(--black-400);
}


.contact-form .form-group
{
    margin-bottom: 25px;
}
.contact-form .form-control
{
    width: 493px;
    height: 52px;
    border: 1px solid transparent;
    box-shadow: var(--shadow-black-100);
    border-radius: 30px;
    padding: 0 24px;
   
    background-color: var(--white);
    transition: 0.5s ease;
    font-family: 'Poppins', sans-serif;
}
.contact-form textarea.form-control
{
    height: 130px;
    padding: 12px;
    resize: none;
}
.contact-form .form-control:focus
{
    border-color: magenta;
}


#msg {


    color: olive;
    margin-bottom: -548px;
    margin-left: 178px;
    
    
    display: block;
}
}

/* header social     SEction */

 .header-social1-icon
{
    position: relative;
    bottom: 30px;
    left: -435px;
    top: -115px;
    width: 100%;
}
 .header-social-icon1::before
{
    position: absolute;
    content: '';
    width: 100%;
    height: 2px;
    background-color: var(--main-color);
    left: -102.5%;
    top: 50%;
    transform: translateY(-50%);
}
.header-social1-icon ul li
{
    display: inline-block;
    margin-left: 13px;
}
.header-social1-icon ul li:first-child
{
    margin-left: 0;
}
.header-social1-icon ul li a
{
    color: green;
    font-size: 20px;
    width: 40px;
    height: 40px;
    display: inline-block;
    line-height: 40px;
    border-radius: 50%;
    border: 1px solid #ccc;
    text-align: center;
    transition: all 0.3s ease-out 0s;
}
.header-social1-icon ul li a:hover
{
    color: var(--white);
    background-color: red;
    border-color: var(--main-color);
}




  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div>
 <a href="index.php" class="btn btn-back px-3">← Back to Main Site</a>
  </div>
  
 <section class="contact section-padding" data-scroll-index="5" id="contact">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="section-title">
            <h4>contact me</h4>
            <h2>get <span>in touch</span></h2>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-4 col-md-5">
          <div class="contact-info">
            <h3>For Any Queries and Support</h3>
            <div class="contact-info-item">
              <i class="fas fa-location-arrow"></i>
              <h4>Location</h4>
              <p>Kutubdia, Cox's Bazar</p>
            </div>
            <div class="contact-info-item">
              <i class="fas fa-envelope"></i>
              <h4>Write to us at</h4>
              <p>mohammadajij120386@gmail.com</p>
            </div>
            <div class="contact-info-item">
              <i class="fas fa-phone"></i>
              <h4>Call us on</h4>
              <p>+880 1609016477</p>
            </div>

            <!-- header social -->
            <div class="header-social1-icon">
              <ul>
                <li><a href="https://www.linkedin.com/in/mohammad-ajij-b5a6aa28a/" target="_blank"><i class="fab fa-linkedin"></i></a>
                </li>
                <li><a href="https://www.facebook.com/Mohammad.ajij.120386"  target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="https://github.com/Ajij120386" target="_blank"><i class="fab fa-github"></i></a></li>


                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
              </ul>
            </div>


          </div>

        </div>
 

        <div class="col-lg-8 col-md-7">
          <div class="contact-form">
            <form name="submit-to-google-sheet" action="">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <input type="text" name="Name" placeholder="Your Name" class="form-control" required>
                  </div>
                </div>

              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <input type="email"  name="Email"  placeholder="Your Email" class="form-control" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <input type="text" name="Subject" placeholder="Subject" class="form-control">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <textarea name="Message" placeholder="Type Your Message Here..." id="" class="form-control" required></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button class="btn-2" type="submit">Send Message</button>
                </div>
              </div>
            </form>

            
          </div>
          <span id="msg"></span>
        </div>
      </div>

      
  </section>


</body>


<script>
  const scriptURL = 'https://script.google.com/macros/s/AKfycbylA4WvJOEsBzhR-97m_YkPX8kNqIZwiSVF44y5OblVxC3nHfvTF6YXEdZLg5ZGMYS7/exec';
  const form = document.forms['submit-to-google-sheet']
  const msg=document.getElementById("msg")

  form.addEventListener('submit', e => {
    e.preventDefault()
    fetch(scriptURL, { method: 'POST', body: new FormData(form)})
      .then(response => {

              msg.innerHTML="Message sent successfullly"
             setTimeout(function(){
                
               msg.innerHTML=""


             },5000)
             form.reset()
      })
      .catch(error => console.error('Error!', error.message))
  })
</script>

</html>
