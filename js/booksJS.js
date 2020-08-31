console.log('This is the books JS');



// 
//const bigRedbtn = document.querySelectorAll('a.bigRed');
//const bookTitles = document.querySelectorAll('.wp-show-posts-entry-title');
//bigRedbtn.forEach((n, i) => n.href = bookTitles[i].href);
// 



// Get the URL of the big red button
const bigRedbtn = document.querySelectorAll('.bigRed');
//Convert into an array
const bigRedbtnArray = Array.from(bigRedbtn);
//Get all the links 
const bookTitles = document.querySelectorAll('.wp-show-posts-entry-title a');
console.log(bookTitles.length);
//Convert into an array
const bookTitlesArray = Array.from(bookTitles);
console.log(bookTitlesArray.length);

for (var i = 0; i < bigRedbtnArray.length; i++) {
    for (var j = 0; j < bookTitlesArray.length; j++) {

        //  what is the links for the book?
        const link = bigRedbtnArray[i].href;
        console.log(link);
        // console.log('bigRedbtn: ', bigRedbtn[i].href);

        //this is the title url to be replaced with the link from bigRedBtn
        const titleLink = bookTitlesArray[j].href;
        console.log(titleLink);

        //So can you do something like this

        //This is where I am stuck at
        //I need to replace each bookTitlesArray[j].href with the value of bigRedbtnArray[i].href

    }
}






// bigRedbtn.forEach(function (link) {
//     //What is the href
//     const linkURL = link.href;
//     console.log("linkURL");
//     console.log(linkURL);
// });



//const bookTitles = document.querySelectorAll('.wp-show-posts-entry-title a');
//console.log(bookTitles);


//Map over bookTitlesArray
// bookTitlesArray.forEach(function (book) {
//     //What is the href
//     const bookURL = book.href;
//     console.log("bookURL");
//     console.log(bookURL);

//     bookURL = ''
// });























