const arrows = document.querySelectorAll(".arrow");
const eventLists = document.querySelectorAll(".event-list");

arrows.forEach((arrow, i) => {
  const itemNumber = eventLists[i].querySelectorAll("img").length;
  let clickCounter = 0;
  arrow.addEventListener("click", () => {
    const ratio = Math.floor(window.innerWidth / 270);
    clickCounter++;
    if (itemNumber - (4 + clickCounter) + (4 - ratio) >= 0) {
      eventLists[i].style.transform = `translateX(${
        eventLists[i].computedStyleMap().get("transform")[0].x.value - 300
      }px)`;
    } else {
      eventLists[i].style.transform = "translateX(0)";
      clickCounter = 0;
    }
  });

  console.log(Math.floor(window.innerWidth / 270));
});

