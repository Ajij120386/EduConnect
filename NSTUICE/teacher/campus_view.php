
 

<div class="image-card">
  <div class="image-card-header">🏞 Campus View</div>
  <div class="slider-container">
    <img src="images/8.jpeg" class="active" alt="Slide 1">
   <img src="images/1.jpg" alt="Slide 2">
    <img src="images/3.jpg" alt="Slide 3">
    <img src="images/4.jpg" alt="Slide 4">
    <img src="images/5.jpg" alt="Slide 5">
    <img src="images/6.jpg" alt="Slide 6">
    <img src="images/7.jpg" alt="Slide 7">
    <img src="images/2.jpg" alt="Slide 8">
    <img src="images/9.jpeg" alt="Slide 9">
    <img src="images/10.jpeg" alt="Slide 10">
    <img src="images/11.jpg" alt="Slide 11">
    <img src="images/12.jpg" alt="Slide 12">
    <img src="images/13.jpg" alt="Slide 13">
    <img src="images/14.jpg" alt="Slide 14">
    <img src="images/15.jpg" alt="Slide 15">
    <img src="images/16.jpg" alt="Slide 16">
    <img src="images/17.jpg" alt="Slide 17">
    <img src="images/18.jpg" alt="Slide 18">
    <img src="images/19.jpg" alt="Slide 19">
  
    <img src="images/21.jpg" alt="Slide 21">
    <img src="images/22.jpg" alt="Slide 22">
    <img src="images/23.jpg" alt="Slide 23">
    <img src="images/24.jpg" alt="Slide 24">
    <img src="images/25.jpg" alt="Slide 25">
    <img src="images/26.jpg" alt="Slide 26">
    <img src="images/27.jpg" alt="Slide 27">
    <img src="images/28.jpg" alt="Slide 28">
    <img src="images/29.jpg" alt="Slide 29">
    <img src="images/30.jpg" alt="Slide 30">
    <img src="images/32.jpg" alt="Slide 31">
  </div>
</div>

<script>
  const slides = document.querySelectorAll('.slider-container img');
  let current = 0;

  setInterval(() => {
    slides[current].classList.remove('active');
    current = (current + 1) % slides.length;
    slides[current].classList.add('active');
  }, 4000); // Change every 4s
</script>

