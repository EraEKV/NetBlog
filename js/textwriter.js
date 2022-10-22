
const words = [
    "играм",
    "космосу",
    "веб-разработке",
    "программированию",
    "разработке игр",
    "новостям с IT",
]


const output = document.getElementById("output")
let charTurn = 0;
let wordTurn = 0;

const typingWord = () => {
    if(charTurn <= words[wordTurn].length) {
        let str = words[wordTurn].substr(0, charTurn)
        output.innerHTML = str
        charTurn++
        setTimeout(typingWord, 100)
    }
    // else if(charTurn == words[words.length - 1]) {
    //     output.classList.add
    // }
    else {
        setTimeout(deleteWord, 800)
    }
}

const deleteWord = () => {
    if(charTurn >= 0) {
        let str = words[wordTurn].substr(0, charTurn)
        output.innerHTML = str
        charTurn--
        setTimeout(deleteWord, 100)
    }
    else {
        wordTurn++
        if(words.length == wordTurn) {
            wordTurn = 0
        }
        setTimeout(typingWord, 150)
    }
}

typingWord()

