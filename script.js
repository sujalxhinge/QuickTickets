document.addEventListener("DOMContentLoaded", async () => {
  try {
    const response = await fetch("images.json");
    const data = await response.json();

    // Filter data for Movies
    const movies = data.filter((item) => item.category === "movies");

    // Populate Movie Slider
    const movieSwiperWrapper = document.getElementById("swiper-wrapper");
    movies.forEach((movie) => {
      const slide = document.createElement("div");
      slide.classList.add("swiper-slide");
      slide.innerHTML = `<img src="${movie.url}" alt="${movie.title}" />`;
      movieSwiperWrapper.appendChild(slide);
    });

    // Initialize Movie Slider
    const movieSwiper = new Swiper(".mySwiper", {
      effect: "coverflow",
      grabCursor: true,
      slidesPerView: "auto",
      loop: true,
      centeredSlides: true,
      centeredSlidesBounds: true,
      updateOnWindowResize: true,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 2.79,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
    });

    // Add Click Functionality for Movie Slides
    movieSwiper.slides.forEach((slide) => {
      slide.onclick = () => {
        movieSwiper.slideToLoop(slide.dataset.swiperSlideIndex);
        movieSwiper.update();
      };
    });

    // Handle issue with the last slide click for Movies
    movieSwiper.on("slideChangeTransitionEnd", () => {
      if (movieSwiper.isEnd) {
        movieSwiper.loopFix();
        movieSwiper.update();
      }
    });
  } catch (error) {
    console.error("Error fetching images", error);
  }
});
