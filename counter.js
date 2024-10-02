document.addEventListener("DOMContentLoaded", function () {
  // Get the countdown element
  const countdownElement = document.getElementById('countdown');
  // Get the launch date from the data attribute
  const launchDate = new Date(countdownElement.getAttribute('data-launch-date')).getTime();

  // Function to update the countdown
  function updateCountdown() {
      const now = new Date().getTime();
      const timeRemaining = launchDate - now;

      // Calculate time components
      const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
      const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

      const formattedDays = String(days).padStart(2, '0');
      const formattedHours = String(hours).padStart(2, '0');
      const formattedMinutes = String(minutes).padStart(2, '0');
      const formattedSeconds = String(seconds).padStart(2, '0');

      let countdownHTML = "";
      if (formattedDays > 0) {
        countdownHTML += `<span class="days">${formattedDays} d</span>`;
      } else {
        countdownHTML += `<span class="days">00</span>`;
      }
      if (formattedHours > 0) {
        countdownHTML += `<span class="hours">${formattedHours}</span>`;
      }
      if (formattedMinutes > 0) {
        countdownHTML += `<span class="minutes">${formattedMinutes}</span>`;
      }
      if (formattedSeconds > 0) {
        countdownHTML += `<span class="seconds">${formattedSeconds}</span>`;
      }

      // Display the countdown
      countdownElement.innerHTML = countdownHTML;

      // If the countdown is finished, display a message
      if (timeRemaining < 0) {
          clearInterval(countdownInterval);
          countdownElement.innerHTML = "Launched!";
      }
  }

  // Update the countdown every second
  const countdownInterval = setInterval(updateCountdown, 1000);
});
