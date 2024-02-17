var question_id, test_id;
var results = 0;
var qna = [];
var questionData;
var index = 0;
let session_id;
$(document).ready(function () {
  // Check if token is storaged in localstorage if there then
  // send request to the server to get the questions
  var exam_token = localStorage.getItem("exam_token");
  if (exam_token != null) {
    getTest(exam_token, index);
  }
  
  // Hide the test contents before entering the test_token
  $("#test-content").hide();
  
  $("#result-content").hide();
  $("#user-info").hide();
  var isEnglish = checkLanguage();
  
  $("#local-lang-change-btn").click(function () {
    if (isEnglish) {
      setLang("local/ar_YE.json");
      coreSetLang(false);
      $("body").attr("dir", "rtl");
      localStorage.setItem("local", "AR");
      $("#navigation-drawer").css({
        right: "-1000px",
        left: "",
      });
    } else {
      setLang("local/en_US.json");
      coreSetLang(true);
      $("body").attr("dir", "ltr");
      localStorage.setItem("local", "EN");
      $("#navigation-drawer").css({
        left: "-1000px",
        right: "",
      });
    }
    isEnglish = !isEnglish;
  });

  // To get the questions and save token in localstorage if test_token is valid
  // In server
  $("#submit-btn").click(function (e) {
    e.preventDefault();
    var test_token = $("#test-id").val();
    getTest(test_token, index);
  });

  // To stop the test and refreshing the site
  $("#stop-test").click(function (e) {
    e.preventDefault();
    endSession();
    removeLocalTokens();
    window.location.reload();
  });

  // Check in server if the answer is correct or no
  $("#send-answer").click(function (e) {
    e.preventDefault();
    console.log(qna);

    var checkedRadioButtons = $('input[name="answer"]:checked');

    if (checkedRadioButtons.length === 1) {
      var selectedValue = checkedRadioButtons.val();
      if (question_id != undefined && test_id != undefined) {
        if (selectedValue === "Correct") {
          sendAnswer(1, question_id, test_id);
        } else if (selectedValue === "Wrong") {
          sendAnswer(0, question_id, test_id);
        }
        $('input[type="radio"]').prop("checked", false);
      }
    } else if (checkedRadioButtons.length === 0) {
      console.log("Both radio buttons are not checked.");
    } else {
      console.log("Error: More than one radio button is checked.");
    }
  });
});

// Function to get the questions from the server using the test_token provided
function getTest(test_token, index) {
  var json = JSON.stringify({
    test_token: test_token,
  });
  console.log(json);

  // Send request to get the questions from the server
  $.ajax({
    type: "POST",
    url: "/test/apis/exam/get_exam/",
    data: json,
    contentType: "application/json",
    success: function (response) {
      var respond = JSON.parse(response);
      question = respond["data"];
      session_id = respond["Session_id"];
      setTest(question, index);
      localStorage.setItem("exam_token", test_token);
    },
    error: function (response) {
      $("#send-req").show();
      $("#test-content").hide();
    },
  });
}

function setTest(question, index) {
  index = this.index;
  var questions_length = question.length;
  if (index < questions_length) {
    $("#question-counter").text(index + 1 + "/" + questions_length);

    $("#question-content").text(question[index]["question_title"]);
    question_id = question[index]["question_id"];
    test_id = question[index]["test_id"];
    questionData = question[index];

    $("#send-req").hide();
    $("#test-content").show();
  } else {
    $("#test-content").hide();
    $("#result-content").show();
    showResultsData();
    removeLocalTokens();
    $("#result-txt").text("You have answered: " + results + " correctly");
  }
}

function sendAnswer(answer, question_id, test_id) {
  $.ajax({
    type: "GET",
    url:
      "/test/apis/exam/send_answer/?answer=" +
      answer +
      "&question_id=" +
      question_id +
      "&test_id=" +
      test_id,
    headers: {
      "session-id": session_id,
    },
    success: function (response) {
      var respond = JSON.parse(response);
      if (respond["Status"] == "Success") {
        if (respond["Message"] == "Vaild answer") {
          results++;
          qna.push({
            question_title: questionData["question_title"],
            question_answer: true,
            user_answer: answer == 1 ? true : false,
          });
        } else {
          qna.push({
            question_title: questionData["question_title"],
            question_answer: false,
            user_answer: answer == 1 ? true : false,
          });
        }

        index++;
        setTest(question, index);
      } else {
      }
    },
  });
}

function removeLocalTokens() {
  localStorage.removeItem("exam_token");
  localStorage.removeItem("questions");
  localStorage.removeItem("exam_token");
}

function endSession() {
  $.ajax({
    type: "GET",
    url: "/test/apis/exam/end_exam_session/",
    success: function (response) {},
  });
}

function showResultsData() {
qna.forEach(e => {
  if(e['question_answer']) {
    $("#results-details").append('<div class="result-item correct_answer">\
    \
    <p class="result-item-txt">' + e['question_title'] +'</p>\
    <p>You answered: ' + e['user_answer'] + '</p>\
    ');
  } else {
    $("#results-details").append('<div class="result-item wrong_answer">\
    \
    <p class="result-item-txt">' + e['question_title'] +'</p>\
    <p>You answered: ' + e['user_answer'] + '</p>\
    <p>The correct answer is: ' + !e['user_answer'] + '</p>\
    ');
  }
});
}
