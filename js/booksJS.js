//Getting The Btns
const bigRedbtn = document.querySelectorAll('a.bigRed');
const bookTitles = document.querySelectorAll('.wp-show-posts-entry-title a');
// Replacing the href with bigRed's href
bookTitles.forEach((n, i) => n.href = bigRedbtn[i].href);

//.forEach iterates through an iterable, in this case a NodeList, and streams 3 things: 
//the current value, an integer representing the current index and the iterable itself. 
//Looking back at the code, we can deduce that n is the current value and i is the integer representing the current index. 
//Knowing that both NodeLists are the same length, 
//we can access bookTitles by using the current index that is streamed by the forEach that is run on bigRedbtn.