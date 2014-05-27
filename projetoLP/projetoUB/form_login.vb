Imports MySql.Data.MySqlClient

Public Class form_login

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim foi As Integer = 1 'se houver erros passa para 0, e não e preciso verificar se o administrador existe na bd'

        If (TextBox1.Text.Length < 4 Or TextBox1.Text.Length > 50) Then
            ErrorProvider1.SetError(TextBox1, "O nome de utilizador deve ter entre 4 e 50 caracteres")
            foi = 0
        End If
        If (TextBox2.Text.Length < 4 Or TextBox2.Text.Length > 50) Then
            ErrorProvider1.SetError(TextBox2, "A palavra-passe deve ter entre 4 e 50 caracteres")
            foi = 0
        End If

        If (foi = 1) Then
            Dim nome As String = TextBox1.Text
            Dim pass As String = TextBox2.Text
            Dim passbd As String = ""
            Dim permissao As Integer = 0
            Dim id_utilizador As Integer = 0

            pass = getSHA1Hash(pass)
            'connecta a bd'
            Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
            Dim sqlQuery As String = "SELECT id, palavrap_sha1, permissao FROM utilizadores WHERE username = '" & nome & "' AND permissao > 0"
            Using sqlConn As New MySqlConnection(connString)
                Using sqlComm As New MySqlCommand()
                    With sqlComm
                        .Connection = sqlConn
                        .CommandText = sqlQuery
                        .CommandType = CommandType.Text
                    End With
                    Try
                        sqlConn.Open()
                        Dim sqlReader As MySqlDataReader = sqlComm.ExecuteReader()
                        While sqlReader.Read()
                            passbd = sqlReader("palavrap_sha1").ToString()
                            id_utilizador = sqlReader("id").ToInt()
                            If (sqlReader("permissao").ToInt() > 0) Then
                                permissao = sqlReader("permissao").ToInt()
                            End If
                            MsgBox(passbd)
                        End While
                    Catch ex As MySqlException
                        MsgBox("excecpçao nº 8137146")
                    End Try
                End Using
            End Using

            

            If (passbd = pass) Then
                '   Session("administrador_id") =
                form_main.Show()
                Me.Close()
            Else
                MsgBox("O nome de utilizador e/ou a palavra-passe são inválidos ou não tem permissão de administrador. pass na bd = " & passbd)
            End If

        End If
    End Sub

    Function getSHA1Hash(ByVal strToHash As String) As String

        Dim sha1Obj As New Security.Cryptography.SHA1CryptoServiceProvider
        Dim bytesToHash() As Byte = System.Text.Encoding.ASCII.GetBytes(strToHash)

        bytesToHash = sha1Obj.ComputeHash(bytesToHash)

        Dim strResult As String = ""

        For Each b As Byte In bytesToHash
            strResult += b.ToString("x2")
        Next

        Return strResult

    End Function
End Class