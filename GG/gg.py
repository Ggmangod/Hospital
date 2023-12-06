from kivy.app import App
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.button import Button
from kivy.uix.textinput import TextInput
from kivy.uix.label import Label

# Declare username as a global variable
username = ''

class PlannerApp(App):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.users_file = 'users.txt'
        self.current_user = None
        self.task_file = None

    def build(self):
        self.layout = BoxLayout(orientation='vertical')

        self.username_input = TextInput(hint_text='Имя пользователя', size_hint=(1, None), height=40)
        self.layout.add_widget(self.username_input)

        self.password_input = TextInput(hint_text='Пароль', password=True, size_hint=(1, None), height=40)
        self.layout.add_widget(self.password_input)

        register_button = Button(text='Зарегистрироваться', size_hint=(1, None), height=40)
        register_button.bind(on_press=self.register_user)
        self.layout.add_widget(register_button)

        login_button = Button(text='Войти', size_hint=(1, None), height=40)
        login_button.bind(on_press=self.login_user)
        self.layout.add_widget(login_button)

        return self.layout

    def register_user(self, instance):
        global username  # Declare username as global
        username = self.username_input.text
        password = self.password_input.text

        if username and password:
            with open(self.users_file, 'a') as file:
                file.write(f'{username}:{password}\n')

            self.username_input.text = ''
            self.password_input.text = ''
            self.layout.add_widget(Label(text='Пользователь зарегистрирован', size_hint=(1, None), height=40))
        else:
            self.layout.add_widget(Label(text='Введите имя пользователя и пароль', size_hint=(1, None), height=40))

    def login_user(self, instance):
        global username  # Declare username as global
        username = self.username_input.text
        password = self.password_input.text

        if username and password:
            with open(self.users_file, 'r') as file:
                users = file.readlines()
                for user in users:
                    stored_username, stored_password = user.strip().split(':')
                    if username == stored_username and password == stored_password:
                        self.current_user = username
                        self.task_file = f'{self.current_user}_tasks.txt'
                        self.layout.clear_widgets()
                        self.show_planner()
                        return
            self.layout.add_widget(Label(text='Неверное имя пользователя или пароль', size_hint=(1, None), height=40))
        else:
            self.layout.add_widget(Label(text='Введите имя пользователя и пароль', size_hint=(1, None), height=40))

    def show_planner(self):
        self.task_input = TextInput(hint_text='Введите задачу', size_hint=(1, None), height=40)
        self.layout.add_widget(self.task_input)

        self.date_input = TextInput(hint_text='Введите дату в формате YYYY-MM-DD', size_hint=(1, None), height=40)
        self.layout.add_widget(self.date_input)

        add_button = Button(text='Добавить задачу', size_hint=(1, None), height=40)
        add_button.bind(on_press=self.add_task)
        self.layout.add_widget(add_button)

        self.task_list = BoxLayout(orientation='vertical', size_hint_y=None)
        self.load_tasks()
        self.layout.add_widget(self.task_list)

    def add_task(self, instance):
        task_text = self.task_input.text
        selected_date = self.date_input.text

        if task_text and selected_date:
            with open(self.task_file, 'a') as file:
                file.write(f'{task_text} - {selected_date}\n')

            task_button = Button(text=f'{task_text} - {selected_date}', size_hint_y=None, height=40)
            self.task_list.add_widget(task_button)
            self.task_input.text = ''
            self.date_input.text = ''

    def load_tasks(self):
        try:
            with open(self.task_file, 'r') as file:
                tasks = file.readlines()
                for task in tasks:
                    task_button = Button(text=task.strip(), size_hint_y=None, height=40)
                    self.task_list.add_widget(task_button)
        except FileNotFoundError:
            pass

if __name__ == '__main__':
    PlannerApp().run()