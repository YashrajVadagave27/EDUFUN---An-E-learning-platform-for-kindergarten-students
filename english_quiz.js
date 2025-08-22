const questions = [
    { type: 'odd', question: 'Which is the odd one out?', options: ['Apple', 'Banana', 'Carrot', 'Grapes'], answer: 'Carrot' },
    { type: 'word', question: 'Which word is spelled correctly?', options: ['Apple', 'Aplle', 'Applle', 'Appl'], answer: 'Apple' },
    { type: 'rhyme', question: 'Which word rhymes with "Cat"?', options: ['Dog', 'Hat', 'Mouse', 'Fish'], answer: 'Hat' },
    { type: 'identify', question: 'Which is a fruit?', options: ['Table', 'Banana', 'Chair', 'Fan'], answer: 'Banana' },
    { type: 'count', question: 'How many letters are in the word "Tree"?', options: ['3', '4', '5', '6'], answer: '4' },
    { type: 'odd', question: 'Which is the odd one out?', options: ['Dog', 'Cat', 'Fish', 'Car'], answer: 'Car' },
    { type: 'word', question: 'Which word is spelled correctly?', options: ['Eagle', 'Eagel', 'Eaglle', 'Egale'], answer: 'Eagle' },
    { type: 'rhyme', question: 'Which word rhymes with "Sun"?', options: ['Run', 'Mouth', 'Cloud', 'Snow'], answer: 'Run' },
    { type: 'identify', question: 'Which is a vegetable?', options: ['Carrot', 'Apple', 'Banana', 'Grapes'], answer: 'Carrot' },
    { type: 'count', question: 'How many sides does a triangle have?', options: ['3', '4', '5', '6'], answer: '3' },
    { type: 'odd', question: 'Which is the odd one out?', options: ['Pineapple', 'Strawberry', 'Peach', 'Carrot'], answer: 'Carrot' },
    { type: 'word', question: 'Which word is spelled correctly?', options: ['Bicycle', 'Bicyle', 'Bicycl', 'Bicyclee'], answer: 'Bicycle' },
    { type: 'rhyme', question: 'Which word rhymes with "Hat"?', options: ['Mat', 'Bat', 'Rat', 'Sat'], answer: 'Mat' },
    { type: 'identify', question: 'Which is an animal?', options: ['Car', 'Table', 'Dog', 'Banana'], answer: 'Dog' },
    { type: 'count', question: 'How many letters are in the word "Elephant"?', options: ['7', '8', '9', '10'], answer: '8' }
];

let currentQuestionIndex = 0;
let score = 0;
const maxQuestions = 10;
let attemptedQuestions = 0;  // Track how many questions have been answered

// Shuffle questions for randomness
function shuffle(array) {
    return array.sort(() => Math.random() - 0.5);
}

// Load a question
function loadQuestion() {
    if (currentQuestionIndex >= maxQuestions) {
        endQuiz();
        return;
    }

    const questionData = questions[currentQuestionIndex];
    document.getElementById('questionText').textContent = questionData.question;
    const optionsContainer = document.getElementById('optionsContainer');
    optionsContainer.innerHTML = '';

    shuffle(questionData.options).forEach(option => {
        const button = document.createElement('button');
        button.textContent = option;
        button.onclick = () => checkAnswer(option, questionData.answer);
        optionsContainer.appendChild(button);
    });
}

// Check if the selected answer is correct and go to next question
function checkAnswer(selected, correct) {
    if (selected === correct) {
        score++;
    }
    attemptedQuestions++;
    currentQuestionIndex++;

    // Load the next question automatically after answering
    if (attemptedQuestions < maxQuestions) {
        loadQuestion();
    } else {
        endQuiz();
    }
}

// End the quiz and submit results
function endQuiz() {
    // Display the final score when all questions are answered
    document.getElementById('quizContent').innerHTML = `<h2>Your Final Score: ${score} / ${maxQuestions}</h2>`;

    // Display the submit button to send the score (hidden initially)
    document.getElementById('submitForm').style.display = 'block';
    document.getElementById('finalScore').value = score;
    // Optionally, automatically submit the form here if you want
    // document.getElementById('submitForm').submit();
}

// Initialize the quiz
loadQuestion();
