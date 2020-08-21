console.log('Register Page JS loaded');

//Get the subscribe btn
const freeRegBtn = document.querySelectorAll('#leaky-paywall-submit');
//Change the innerHtml to the text we want.
freeRegBtn[0].innerText = "Register";



//Slice method example
let text = 'This is a new bunch of string text';

let newTextText = text.toUpperCase();
console.log(newTextText);

//So if you are to use it it can be like this:

console.log(text.slice(0, 4));

console.log(text.slice(4));

console.log(text.slice(3, -2));
