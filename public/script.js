document.addEventListener('DOMContentLoaded', () => {
  const addTaskBtn = document.querySelector('button.add-task');
  const addTaskForm = document.getElementById('add-task-form');
  if (addTaskBtn) addTaskBtn.addEventListener('click', showForm);

  function showForm() {
    if (addTaskBtn) addTaskBtn.classList.add('d-none');
    if (addTaskForm) addTaskForm.classList.remove('d-none');
  }
});
