// Function to validate the Add Portfolio form
function validateForm() {
  var symbol = document.getElementById("symbol").value;
  var name = document.getElementById("name").value;
  var quantity = document.getElementById("quantity").value;
  var price = document.getElementById("price").value;

  // Add your validation logic here
  // Example: check if the inputs are not empty or meet certain requirements

  if (symbol === "" || name === "" || quantity === "" || price === "") {
    alert("Please fill in all the fields.");
    return false;
  }

  return true;
}

// Function to format the price input with two decimal places
function formatPriceInput() {
  var priceInput = document.getElementById("price");
  var price = parseFloat(priceInput.value);

  // Format the price with two decimal places
  if (!isNaN(price)) {
    priceInput.value = price.toFixed(2);
  }
}

// Function to clear the form inputs
function clearForm() {
  document.getElementById("symbol").value = "";
  document.getElementById("name").value = "";
  document.getElementById("quantity").value = "";
  document.getElementById("price").value = "";
}

// Function to handle the form submission
function handleSubmit(event) {
  event.preventDefault(); // Prevent the default form submission

  // Validate the form
  if (!validateForm()) {
    return;
  }

  // Get the form data
  var symbol = document.getElementById("symbol").value;
  var name = document.getElementById("name").value;
  var quantity = document.getElementById("quantity").value;
  var price = document.getElementById("price").value;
  var transactionType = document.getElementById("transaction_type").value;

  // Perform any additional processing or validation if needed

  // Submit the form data to the server
  var form = document.getElementById("add-portfolio-form");
  form.submit();
}

// Event listener for the form submission
document
  .getElementById("add-portfolio-form")
  .addEventListener("submit", handleSubmit);
