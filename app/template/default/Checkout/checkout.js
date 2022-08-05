const STRIPE_PK = document.getElementById("stripe-pk").innerHTML;
const CONVERT_MONEY = 23358;

const stripe = Stripe(STRIPE_PK);

const elements = stripe.elements();
const card = elements.create("card");
card.mount("#card-element");

var display_card = true;

var card_select_label = document.getElementById("card-select-label");
var card_element_label = document.getElementById("card-element-label");

var card_select = document.getElementById("card-select");
var card_element = document.getElementById("card-element");

card_select.style.display = "none";
card_element.style.display = "none";

card_select_label.onclick = function () {
  card_select.style.display = "block";
  card_element.style.display = "none";
  card_element.removeAttribute("disabled");
  display_card = false;
};

card_element_label.onclick = function () {
  card_element.style.display = "block";
  card_select.style.display = "none";
  card_select.setAttribute("disabled", "disabled");
};

const form = document.getElementById("payment-form");
form.addEventListener("submit", handleForm);

async function handleForm(event) {
  event.preventDefault();

  if (display_card) {
    const personResult = await stripe.createSource(
      card,
      function (status, result) {
        if (result.error) {
          console.log(result.error.message);
        } else {
          console.log(result);
        }
      }
    );

    if (personResult.source) {
      console.log(personResult.source);
      document.querySelector("#token").value = personResult.source.id;
      document.querySelector("#total_price").value =
        Math.round(totalPrice / CONVERT_MONEY) * 10;
      form.submit();
    } else if (personResult.error) {
      console.log("Error");
    }
  } else {
    document.querySelector("#token").value =
      document.querySelector("#card-select").value;
    document.querySelector("#total_price").value =
      Math.round(totalPrice / CONVERT_MONEY) * 10;
    form.submit();
  }
}
