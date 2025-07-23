<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>NSTU Homepage Clone</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: #f4f4f4;
    }

    .top-bar {
      background: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #e0e0e0;
    }

    .top-bar .left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .top-bar .right button {
      margin-left: 10px;
      padding: 5px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
    }

    .login {
      background-color: #ff4d4d;
      color: white;
    }

    .signup {
      background-color: #3399ff;
      color: white;
    }

    .header {
      padding: 10px 20px;
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .header img {
      height: 60px;
    }

    .header-text h1 {
      font-size: 20px;
      color: #003366;
    }

    .hero {
      position: relative;
      height: 400px;
      background: url('images/slider2.jpg') no-repeat center center/cover;
    }

    .hero .overlay {
      position: absolute;
      bottom: 30px;
      left: 0;
      right: 0;
      text-align: center;
      padding: 20px;
      color: white;
      background: rgba(0, 0, 0, 0.4);
    }

    .overlay h2 {
      font-size: 28px;
      font-weight: bold;
    }

    .overlay p {
      margin-top: 8px;
      font-size: 14px;
      line-height: 1.4;
    }

    .main-content {
      display: flex;
      padding: 30px;
      gap: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .section-box {
      flex: 1;
      background: white;
      padding: 20px;
      border: 1px solid #ccc;
    }

    .section-box h3 {
      background: #001f4d;
      color: white;
      padding: 10px;
      font-size: 16px;
    }

    .section-box ul {
      list-style: none;
      padding: 10px;
    }

    .section-box ul li {
      padding: 8px;
      border-bottom: 1px solid #eee;
    }

    .section-box p {
      margin-top: 10px;
      font-size: 15px;
      line-height: 1.6;
    }

    .icon-section {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 40px;
      background: #fff;
      padding: 40px 20px;
    }

    .icon-box {
      text-align: center;
    }

    .circle-wrapper {
      position: relative;
      width: 100px;
      height: 130px;
      margin: auto;
    }

    .hover-circle {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      margin: auto;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: #2e1f44;
      color: white;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
      z-index: 1;
    }

    .circle-wrapper:hover .hover-circle {
      opacity: 1;
    }

    .circle {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      margin: auto;
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: #6b2d91;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      color: white;
      z-index: 2;
      border: 5px solid #d1c5e0;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .circle-wrapper:hover .circle {
      transform: translateY(10px);
    }

    .icon-box p {
      margin-top: 12px;
      font-weight: bold;
      color: #333;
      font-size: 14px;
      letter-spacing: 0.5px;
    }
  </style>
</head>
</head>
<body>

  <div class="top-bar">
    <div class="left">
      <span>📞 02334496522</span>
      <span>📠 88027791052</span>
      <span>✉ registrar@office.nstu.edu.bd</span>
    </div>
    <div class="right">
      <button class="login">Login</button>
      <button class="signup">Signup</button>
    </div>
  </div>

  <div class="header">
    <img src="nstu-logo.png" alt="NSTU Logo">
    <div class="header-text">
      <h1>NOAKHALI SCIENCE AND TECHNOLOGY UNIVERSITY</h1>
      <p>Noakhali 3814, Bangladesh</p>
    </div>
  </div>

  <div class="hero">
    <div class="overlay">
      <h2>NOAKHALI SCIENCE AND TECHNOLOGY UNIVERSITY</h2>
      <p>Since 2005<br>
        Noakhali Science and Technology University (Bengali: নোাখালী বিজ্ঞান ও প্রযুক্তি বিশ্ববিদ্যালয়) (known as NSTU) is a public university in the coastal terrain Noakhali of Bangladesh. It is the 27th public university and fifth science and technology university in Bangladesh. Its foundation stone was laid on 11 October 2003 and academic activities started on 22 June 2006.
      </p>
    </div>
  </div>

  <div class="icon-section">
    <div class="icon-box">
      <div class="circle-wrapper">
        <div class="hover-circle">Click Here</div>
        <div class="circle"><i class="fa fa-graduation-cap"></i></div>
      </div>
      <p>ABOUT US →</p>
    </div>
    <div class="icon-box">
      <div class="circle-wrapper">
        <div class="hover-circle">Click Here</div>
        <div class="circle"><i class="fa fa-university"></i></div>
      </div>
      <p>ADMINISTRATION →</p>
    </div>
    <div class="icon-box">
      <div class="circle-wrapper">
        <div class="hover-circle">Click Here</div>
        <div class="circle"><i class="fa fa-book"></i></div>
      </div>
      <p>ADMISSION →</p>
    </div>
    <div class="icon-box">
      <div class="circle-wrapper">
        <div class="hover-circle">Click Here</div>
        <div class="circle"><i class="fa fa-users"></i></div>
      </div>
      <p>APPOINTMENTS →</p>
    </div>
  </div>

  <div class="main-content">
    <div class="section-box">
      <h3>OVERVIEW NSTU</h3>
      <p>
        The natural setting along with its human inhabitants of the southern region of Bangladesh has been endowed with its vast and immense potentials that can truly be harnessed only through creation of a band of skilled manpower equipped with the latest knowledge of science and technology. In the beginning of this era and apprehending all justifications required for higher modern education of science and technology and spreading wisdom in every field of knowledge.
        <br><br>
        The university was established on 11th October 2003 and started its academic activities on June 22, 2006.
      </p>
    </div>

    <div class="section-box">
      <h3>FACULTY AND DEPARTMENT</h3>
      <ul>
        <li>Faculty of Engineering and Technology</li>
        <li>Faculty of Science</li>
        <li>Faculty of Social Science</li>
        <li>Faculty of Business Administration</li>
        <li>Faculty of Education</li>
        <li>Faculty of Law</li>
      </ul>
    </div>
  </div>

</body>
</html>
