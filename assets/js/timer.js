// 1. Utwórz zmienne globalne dla wartości `sessionLength`, `breakLength`, `sessionCount` i `timer`
let sessionLength;
let breakLength;
let sessionCount;
let timer;

// 2. Ustaw wartości początkowe zmiennych
let currentSession = 1;
let minutes;
let seconds;
let isSession;
let isLastSession;

// 3. Ustaw funkcję, która oblicza czas.
function countdown() {
  let timeElement = document.getElementById("time");

  // ustawienie formatu czasu
  let formattedMinutes = ("0" + minutes).slice(-2);
  let formattedSeconds = ("0" + seconds).slice(-2);

  // aktualizacja czasu wyświetlanego w elemencie html
  if (isSession) {
    timeElement.innerHTML =
      "Session " + currentSession + "/" + sessionCount + " - " + formattedMinutes + ":" + formattedSeconds;
  } else {
    if (currentSession < sessionCount) {
      timeElement.innerHTML = "Break - " + formattedMinutes + ":" + formattedSeconds + " left";
    } else {
      isLastSession = true;
      timeElement.innerHTML = "End of Study!";
      let endButton = document.getElementById("end-button");
      endButton.style.display = "block";
    }
  }

  // sprawdź czy czas sesji/przerwy minął
  if (minutes === 0 && seconds === 0) {
    if (isSession) {
      isSession = false;
      if (currentSession < sessionCount) {
        minutes = breakLength;
        timeElement.innerHTML = "Break - " + formattedMinutes + ":" + formattedSeconds + " left";
      } else {
        isLastSession = true;
        timeElement.innerHTML = "End of Study!";
        let endButton = document.getElementById("end-button");
        endButton.style.display = "block";
      }
    } else {
      currentSession++;
      isSession = true;
      minutes = sessionLength;
      timeElement.innerHTML =
        "Session " + currentSession + "/" + sessionCount + " - " + formattedMinutes + ":" + formattedSeconds;
    }
  }

  // aktualizacja czasu
  if (seconds === 0) {
    seconds = 59;
    minutes--;
  } else {
    seconds--;
  }
}

// 4. Ustaw funkcję start, która będzie uruchamiała stoper i przydzielając mu wartość dla czasu sesji.
function start() {
  let startButton = document.getElementById("start-button");
  let pauseButton = document.getElementById("pause-button");
  startButton.style.display = "none";
  pauseButton.style.display = "block";
  countdown();
  timer = setInterval(countdown, 1000);
}

// 5. Ustaw funkcję pauzującą, która zatrzymuje stoper.
function pause() {
  let startButton = document.getElementById("start-button");
  let pauseButton = document.getElementById("pause-button");
  startButton.style.display = "block";
  pauseButton.style.display = "none";
  clearInterval(timer);
}

// 6. Ustaw funkcję kończącą sesję, która zatrzyma stoper i wyświetli przycisk End Session.
function endSession() {
  clearInterval(timer);
  let endButton = document.getElementById("end-button");
  endButton.style.display = "block";
}

// eksportuj funkcje
export { start, pause, endSession };