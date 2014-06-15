Imports System
Imports System.Net
Imports System.IO
Imports System.Text


Public Class Class1

    Public Sub upload_ftp(ByVal _FileName As String, ByVal _UploadPath As String)

        _UploadPath = "ftp://projetos.epcjc.net/~i07351/" + _UploadPath
        Dim _FTPUser As String = "i07351"
        Dim _FTPPass As String = "trabfef5"
        Dim _FileInfo As New System.IO.FileInfo(_FileName)

        ' Create FtpWebRequest object from the Uri provided
        Dim _FtpWebRequest As System.Net.FtpWebRequest = CType(System.Net.FtpWebRequest.Create(New Uri(_UploadPath)), System.Net.FtpWebRequest)

        ' Provide the WebPermission Credintials
        _FtpWebRequest.Credentials = New System.Net.NetworkCredential(_FTPUser, _FTPPass)

        ' By default KeepAlive is true, where the control connection is not closed
        ' after a command is executed.
        _FtpWebRequest.KeepAlive = False

        ' set timeout for 20 seconds
        _FtpWebRequest.Timeout = 20000

        ' Specify the command to be executed.
        _FtpWebRequest.Method = System.Net.WebRequestMethods.Ftp.UploadFile

        ' Specify the data transfer type.
        _FtpWebRequest.UseBinary = True

        ' Notify the server about the size of the uploaded file
        _FtpWebRequest.ContentLength = _FileInfo.Length

        ' The buffer size is set to 2kb
        Dim buffLength As Integer = 2048
        Dim buff(buffLength - 1) As Byte

        ' Opens a file stream (System.IO.FileStream) to read the file to be uploaded
        Dim _FileStream As System.IO.FileStream = _FileInfo.OpenRead()

        Try
            ' Stream to which the file to be upload is written
            Dim _Stream As System.IO.Stream = _FtpWebRequest.GetRequestStream()

            ' Read from the file stream 2kb at a time
            Dim contentLen As Integer = _FileStream.Read(buff, 0, buffLength)

            ' Till Stream content ends
            Do While contentLen <> 0
                ' Write Content from the file stream to the FTP Upload Stream
                _Stream.Write(buff, 0, contentLen)
                contentLen = _FileStream.Read(buff, 0, buffLength)
            Loop

            ' Close the file stream and the Request Stream
            _Stream.Close()
            _Stream.Dispose()
            _FileStream.Close()
            _FileStream.Dispose()
        Catch ex As Exception
            MessageBox.Show(ex.Message, "Upload Error", MessageBoxButtons.OK, MessageBoxIcon.Error)
        End Try
    End Sub

    Public Sub download_ftp(ByVal caminho As String, ficheiro As String)
        My.Computer.Network.DownloadFile("ftp://projetos.epcjc.net/" + caminho, "C:\Temp\" + ficheiro, "i07351", "trabfef5")
    End Sub

    Public Sub apagarficheiro_ftp(ByVal caminho As String)

        Dim request As System.Net.FtpWebRequest = DirectCast(System.Net.WebRequest.Create("ftp://projetos.epcjc.net/" + caminho), System.Net.FtpWebRequest)

        request.Credentials = New System.Net.NetworkCredential("i07351", "trabfef5")

        request.Method = System.Net.WebRequestMethods.Ftp.DeleteFile
        Dim response As FtpWebResponse = request.GetResponse

        Console.WriteLine("Delete status: {0}", response.StatusDescription)

        response.Close()

    End Sub

    Public Sub apagarpasta_ftp(ByVal caminho As String)
        Dim folder As String = "ftp://projetos.epcjc.net/" + caminho
        Dim ftpReq As FtpWebRequest = WebRequest.Create(folder)
        ftpReq.Method = WebRequestMethods.Ftp.RemoveDirectory
        ftpReq.Credentials = New NetworkCredential("i07351", "trabfef5")
        Dim ftpResp As FtpWebResponse = ftpReq.GetResponse
        ftpResp.Close()
    End Sub

    Public Sub apagarconteudopasta_ftp(ByVal caminho As String)
        Dim oFTP As FtpWebRequest = CType(FtpWebRequest.Create("ftp://projetos.epcjc.net/" & caminho), FtpWebRequest)
        oFTP.Credentials = New NetworkCredential("i07351", "trabfef5", "2222")
        oFTP.KeepAlive = True
        oFTP.Method = WebRequestMethods.Ftp.ListDirectory
        Dim response As FtpWebResponse = CType(oFTP.GetResponse, FtpWebResponse)
        Dim sr As StreamReader = New StreamReader(response.GetResponseStream)
        Dim str As String = sr.ReadLine
        While str IsNot Nothing
            apagarficheiro_ftp(str)
            str = sr.ReadLine
        End While
        sr.Close()
        response.Close()
        oFTP = Nothing
    End Sub


End Class
