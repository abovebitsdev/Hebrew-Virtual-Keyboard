document.addEventListener('DOMContentLoaded', appendKeyboards, false); // Waiting until DOM is Load and call keyboard icon function

document.addEventListener('DOMContentLoaded', openKeyboard, false); // Waiting until DOM is Load and call open keyboard function

function appendKeyboards() { // Creates function
    const inputsWithKeyboard = document.querySelectorAll(".addKeyboard div input, .addKeyboard div textarea");  // Gets all inputs with keyboard classes
    inputsWithKeyboard.forEach(function(element) { // Foreach element loop
      const inputWidth = element.offsetWidth; // Takes input width in keyboard class li container
      const typeIcon = element.dataset.type.toLowerCase(); // Takes type of input keyboard button (text/image)
      const paragraph = document.createElement("p"); // Creates empty p element
      if(typeIcon == "text") {
        const iconText = element.dataset.text; // Takes text from data attribute
        paragraph.innerHTML = "<span title='"+ element.dataset.title +"'>" + iconText + "</span>"; // Appends text to paragraph
      } else if(typeIcon == "image") {
        const iconPath = element.dataset.image; // Takes image path from data attribute
        paragraph.innerHTML = "<img src='" + iconPath + "' title='"+ element.dataset.title +"' alt='Virtual keyboard icon'>"; // Appends path to src image into paragraph
      }
      element.closest(".addKeyboard div").appendChild(paragraph); // Appends paragraph element to div
      const paragraphWidth = paragraph.offsetWidth; // Takes width of new paragraph
      element.closest(".addKeyboard div").querySelector("p").style.left = inputWidth - paragraphWidth - 10 + "px"; // Moves created paragraph element inside div
      element.style.paddingRight = paragraphWidth + 20 + "px"; // Padding right into input or textarea by element width
    })
};

function openKeyboard() { // Creates function
    jQuery(".addKeyboard div p").on('click',function(){ // Adds ivent on click initialize keyboard
      jQuery(this).parent().find("input, textarea").keyboard({  // Init keyboard on input
        language: ['he'],
        layout : 'hebrew',
        openOn   : null,
        customLayout: {
          "name":"Hebrew (\u05e2\u05d1\u05e8\u05d9\u05ea)",
          "rtl":true,
          "normal":[
            "~ 1 2 3 4 5 6 7 8 9 0 - = {b}",
            "{t} / ' \u05e7 \u05e8 \u05d0 \u05d8 \u05d5 \u05df \u05dd \u05e4 \\ {enter}",
            "\u05e9 \u05d3 \u05d2 \u05db \u05e2 \u05d9 \u05d7 \u05dc \u05da \u05e3 , ] [",
            "{s} \u05d6 \u05e1 \u05d1 \u05d4 \u05e0 \u05de \u05e6 \u05ea \u05e5 . {s}",
            "{space} {alt} {accept}"
          ],
          "shift":[
            "` ! @ # $ % ^ & * ) ( _ + {b}",
            "{t} Q W E R T Y U I O P | {enter}",
            "A S D F G H J K L : \" } {",
            "{s} Z X C V B N M > < ? {s}",
            "{space} {alt} {accept}"
          ],
          "alt":[
            "{empty} {empty} {empty} {empty} \u20aa {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {b}",
            "{t} {empty} {empty} \u20ac {empty} {empty} {empty} \u05f0 {empty} {empty} {empty} {empty} {enter}",
            "{empty} {empty} {empty} {empty} {empty} \u05f2 \u05f1 {empty} {empty} {empty} {empty} {empty} {empty}",
            "{s} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {s}",
            "{space} {alt} {accept}"
          ],
          "alt-shift":[
            "{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {b}",
            "{t} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {enter}",
            "{empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty}",
            "{s} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {empty} {s}",
            "{space} {alt} {accept}"
          ],
          "lang":["he"]
        }
      });
      jQuery(this).parent().find("input ,textarea").getkeyboard().reveal();  // Opens keyboard by clicking on keyboard
      const inputID = jQuery(this).parent().find("input, textarea").attr("id") // Takes ID of clicked input or textarea
      positionKeyboardPopup(inputID); // Call a function to position popup
    });
};


function positionKeyboardPopup(inputID) { // Creates function
  const inputCords = document.getElementById(inputID).getBoundingClientRect(); // Gets cordinates of clicked input or textarea
  document.querySelector(".ui-keyboard").style.left = inputCords.left + scrollX + "px";  // Adds position on left
  document.querySelector(".ui-keyboard").style.top = inputCords.top + scrollY + "px";  // Adds position on top
};
