document.addEventListener('DOMContentLoaded', () => {
    const timeLimit = 60; // Total time in seconds
    let timeLeft = timeLimit;
    let currentQuestionIndex = 0;
    const totalQuestions = questions.length;

    const timeDisplay = document.getElementById('timeLeft');
    const timerCanvas = document.getElementById('timerCanvas');
    const ctx = timerCanvas.getContext('2d');
    const questionContainer = document.getElementById('questionContainer');
    const prevButton = document.getElementById('prevButton');
    const nextButton = document.getElementById('nextButton');
    const submitButton = document.getElementById('submitButton');

    // Array to store selected answers
    let selectedAnswers = new Array(totalQuestions).fill(null);

    // Function to draw the circular timer
    function drawTimer() {
        ctx.clearRect(0, 0, timerCanvas.width, timerCanvas.height);
        ctx.lineWidth = 8;
        ctx.strokeStyle = '#FF7E00'; // Orange color
        ctx.beginPath();
        const endAngle = ((timeLimit - timeLeft) / timeLimit) * 2 * Math.PI - Math.PI / 2;
        ctx.arc(50, 50, 40, -Math.PI / 2, endAngle, false);
        ctx.stroke();
    }

    // Countdown timer
    const countdown = setInterval(() => {
        timeLeft--;
        timeDisplay.textContent = timeLeft;
        drawTimer();
        if (timeLeft <= 0) {
            clearInterval(countdown);
            submitQuiz(); // Auto-submit when timer ends
        }
    }, 1000);

    function loadQuestion(index) {
        const question = questions[index];
        questionContainer.innerHTML = `
            <p class="question-text">${question.question_text}</p>
            ${question.image_path ? `<img src="uploads/${question.image_path}" alt="Question Image" style="width:30rem; height:200px; object-fit: contain; margin-bottom: 10px;">` : ""}
            <div class="options" data-correct-answer="${question.correct_answer}">  
                <label class="option">
                    <input type="radio" name="answer" value="${question.option1}" ${selectedAnswers[index] === question.option1 ? 'checked' : ''}> ${question.option1}
                </label>
                <label class="option">
                    <input type="radio" name="answer" value="${question.option2}" ${selectedAnswers[index] === question.option2 ? 'checked' : ''}> ${question.option2}
                </label>
                <label class="option">
                    <input type="radio" name="answer" value="${question.option3}" ${selectedAnswers[index] === question.option3 ? 'checked' : ''}> ${question.option3}
                </label>
            </div>
        `;
    
        // Add event listener for options
        const optionsContainer = document.querySelector('.options');
        optionsContainer.addEventListener('change', (e) => {
            const selectedOption = e.target;
            const selectedLabel = selectedOption.closest('label');
            const correctAnswer = optionsContainer.getAttribute('data-correct-answer');
    
            // Store selected answer for the current question
            selectedAnswers[index] = selectedOption.value;
    
            // Reset all options' styling
            optionsContainer.querySelectorAll('label').forEach(label => {
                label.classList.remove('correct', 'incorrect');
            });
    
            // Apply styling based on correctness
            if (selectedOption.value === correctAnswer) {
                selectedLabel.classList.add('correct');
            } else {
                selectedLabel.classList.add('incorrect');
            }
        });
    }
    

    function submitQuiz() {
        let score = 0;

        // Calculate score based on selected answers
        selectedAnswers.forEach((answer, index) => {
            if (answer === questions[index].correct_answer) {
                score++;
            }
        });

        window.location.href = `submit_test.php?score=${score}&total=${totalQuestions}`;
    }

    // Event listeners for Previous and Next buttons
    prevButton.addEventListener('click', () => {
        if (currentQuestionIndex > 0) {
            currentQuestionIndex--;
            loadQuestion(currentQuestionIndex);
            toggleButtons();
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentQuestionIndex < totalQuestions - 1) {
            currentQuestionIndex++;
            loadQuestion(currentQuestionIndex);
            toggleButtons();
        } else if (currentQuestionIndex === totalQuestions - 1) {
            submitButton.style.display = 'inline-block';
        }
    });

    submitButton.addEventListener('click', () => {
        clearInterval(countdown); // Stop the timer if user submits early
        submitQuiz();
    });

    // Toggle button visibility
    function toggleButtons() {
        prevButton.style.display = currentQuestionIndex === 0 ? 'none' : 'inline-block';
        nextButton.style.display = currentQuestionIndex === totalQuestions - 1 ? 'none' : 'inline-block';
        submitButton.style.display = currentQuestionIndex === totalQuestions - 1 ? 'inline-block' : 'none';
    }

    // Initial load
    loadQuestion(currentQuestionIndex);
    toggleButtons();
});
